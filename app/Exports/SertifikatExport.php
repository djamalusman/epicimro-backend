<?php

namespace App\Exports;

use App\Models\SertifikatModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class SertifikatExport implements FromCollection, WithHeadings, WithMapping
{

    // Konstruktor menerima ID dari controller
    public function __construct()
    {

    }

    /**
     * Mengambil data berdasarkan ID
     */
    public function collection()
    {
        return SertifikatModel::select('nama_peserta', 'email', 'nama_training', 'tanggal_training', 'tanggal_kadauarsa_srt', 'permanent_srt', 'no_sertifikat')->get();

    }

    /**
     * Menentukan heading (kolom) untuk file Excel
     */
    public function headings(): array
    {
        return [
            'Nama Peserta',
            'Email',
            'Nama Training',
            'Tanggal Training',
            'Tanggal Status Sertifikat',
            'Status Sertifikat',
            'Nomor Sertifikat'
        ];
    }

    /**
     * Memformat data yang akan di-export, termasuk format tanggal Y-m-d dan kondisi permanent_srt
     */
    public function map($row): array
    {
        return [
            $row->nama_peserta,
            $row->email,
            $row->nama_training,
            // Memastikan tanggal_training di-convert ke format Carbon sebelum format
            $row->tanggal_training ? Carbon::parse($row->tanggal_training)->format('Y-m-d') : '',
            $row->tanggal_kadauarsa_srt ? Carbon::parse($row->tanggal_kadauarsa_srt)->format('Y-m-d') : '',
            // Pengecekan apakah sertifikat sudah kadaluarsa, permanent, atau aktif
            Carbon::parse($row->tanggal_kadauarsa_srt)->lt(Carbon::now())
                ? 'Kadaluarsa'
                : ($row->permanent_srt == 1
                    ? 'Permanent'
                    : 'Aktif'),
            $row->no_sertifikat
        ];
    }
}
