<?php

namespace App\Imports;
use App\Models\SertifikatModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Carbon;
class SertifikatImport implements ToCollection,WithHeadingRow
{
    public $duplicateData = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $tanggalTraining = Date::excelToDateTimeObject($row["tanggal_training"])->format('Y-m-d');
            $nosertifikat = $row["no_urut"] . "/" . $row["kode_training"] . "/" . $row["kode_sertifikasi"] ."/". $row["tahun"];
            // Cek apakah 'nama_peserta' sudah ada di database 4567
            $existingRecord = SertifikatModel::where('no_sertifikat', $nosertifikat)->first();

            $errorMessages = [];

            // Validasi $row[4] harus 6 angka
            if (!preg_match('/^\d{6}$/', $row["no_urut"])) {
                $errorMessages[] = ' Kolom Nomor Urut harus 6 angka '.$row["no_urut"].'';
            }

            // Validasi $row[5] harus 3 huruf
            if (!preg_match('/^[A-Za-z]{3}$/', $row["kode_training"])) {
                $errorMessages[] = 'Kolom Kode training harus 3 huruf '.$row["kode_training"].'';
            }

            // Validasi $row[6] harus 6 huruf
            if (!preg_match('/^[A-Za-z]{6}$/', $row["kode_sertifikasi"])) {
                $errorMessages[] = 'Kolom kode_sertifikasi  harus 6 huruf '. $row["kode_sertifikasi"] .'';
            }

            // Validasi $row[7] harus 4 angka
            if (!preg_match('/^\d{4}$/', $row["tahun"])) {
                $errorMessages[] = 'Kolom tahun harus berupa 4 angka '. $row["tahun"] .'';
            }

            if ($existingRecord) {
                // Jika data duplikat, tambahkan ke array $duplicateData
                $errorMessages[] = 'Nomor Sertifikat duplikat '. $nosertifikat .'';
            }
            if (!empty($errorMessages)) {
                $this->duplicateData[] = [
                    'row_data' => $row,          // Data row yang salah
                    'errors' => $errorMessages,  // Pesan error
                ];
            } else {
                // Jika tidak duplikat, simpan ke database


                SertifikatModel::create([
                    'nama_peserta' => $row["nama_peserta"],
                    'email' => $row["email_peserta"],
                    'nama_training' => $row["nama_training"],
                    'tanggal_training' => $tanggalTraining,
                    'no_urut_srt' => $row["no_urut"],
                    'kode_category_training_srt' => $row["kode_training"],
                    'kode_srt' => $row["kode_sertifikasi"],
                    'tahun_training_srt' => $row["tahun"],
                    'no_sertifikat' => $nosertifikat,
                    'status' => 1
                ]);
            }
        }
    }


    // Tambahkan metode untuk mendapatkan data duplikat
    public function getDuplicateData()
    {
        return $this->duplicateData;
    }
}
