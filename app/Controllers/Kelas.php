<?php

namespace App\Controllers;

use App\Models\KelasModel;

class Kelas extends BaseController
{
    protected $kelasModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Kelas',
            'kelas' => $this->kelasModel->findAll()
        ];

        return view('kelas/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Kelas'
        ];

        return view('kelas/create', $data);
    }

    public function save()
    {
        if (!$this->validate($this->kelasModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kelasModel->save([
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'wali_kelas' => $this->request->getPost('wali_kelas')
        ]);

        return redirect()->to('/kelas')->with('message', 'Data kelas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Data Kelas',
            'kelas' => $this->kelasModel->find($id)
        ];

        if (empty($data['kelas'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data kelas tidak ditemukan');
        }

        return view('kelas/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate($this->kelasModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kelasModel->update($id, [
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'wali_kelas' => $this->request->getPost('wali_kelas')
        ]);

        return redirect()->to('/kelas')->with('message', 'Data kelas berhasil diupdate');
    }

    public function delete($id)
    {
        $kelas = $this->kelasModel->find($id);
        if (empty($kelas)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data kelas tidak ditemukan');
        }

        $this->kelasModel->delete($id);
        return redirect()->to('/kelas')->with('message', 'Data kelas berhasil dihapus');
    }
}
