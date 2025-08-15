<?php

namespace App\Controllers;

use App\Models\KelasModel;
use CodeIgniter\Controller;

class KelasController extends Controller
{
    protected $kelasModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        $data['kelas'] = $this->kelasModel->findAll();
        return view('kelas/index', $data);
    }

    public function create()
    {
        return view('kelas/create');
    }

    public function store()
    {
        // Validation rules
        $rules = [
            'nama_kelas' => [
                'rules' => 'required|min_length[1]|max_length[50]',
                'errors' => [
                    'required' => 'Nama kelas harus diisi',
                    'min_length' => 'Nama kelas terlalu pendek',
                    'max_length' => 'Nama kelas terlalu panjang (maksimal 50 karakter)'
                ]
            ],
            'wali_kelas' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama wali kelas harus diisi',
                    'min_length' => 'Nama wali kelas terlalu pendek (minimal 3 karakter)',
                    'max_length' => 'Nama wali kelas terlalu panjang (maksimal 100 karakter)'
                ]
            ]
        ];

        // Run validation
        if (!$this->validate($rules)) {
            // If validation fails, return to form with errors
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'nama_kelas' => $this->request->getPost('nama_kelas'),
                'wali_kelas' => $this->request->getPost('wali_kelas')
            ];

            $this->kelasModel->insert($data);
            return redirect()->to('/kelas')->with('success', 'Data kelas berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log the error for debugging
            log_message('error', 'Error adding class: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan kelas. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $data['kelas'] = $this->kelasModel->find($id);
        return view('kelas/edit', $data);
    }

    public function update($id)
    {
        $data = [
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'wali_kelas' => $this->request->getPost('wali_kelas')
        ];

        $this->kelasModel->update($id, $data);
        return redirect()->to('/kelas')->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->kelasModel->delete($id);
        return redirect()->to('/kelas')->with('success', 'Data kelas berhasil dihapus!');
    }

    public function exportPdf()
    {
        $data['kelas'] = $this->kelasModel->findAll();

        $dompdf = new \Dompdf\Dompdf();
        $html = view('kelas/pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Daftar_Kelas.pdf', ['Attachment' => true]);
    }

    public function exportExcel()
    {
        $kelas = $this->kelasModel->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Kelas');
        $sheet->setCellValue('C1', 'Wali Kelas');

        // Data
        $row = 2;
        foreach ($kelas as $index => $k) {
            $sheet->setCellValue('A' . $row, ($index + 1));
            $sheet->setCellValue('B' . $row, $k['nama_kelas']);
            $sheet->setCellValue('C' . $row, $k['wali_kelas']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar_Kelas.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
}
