<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends BaseModel
{
   protected $builder;

   public function __construct()
   {
      parent::__construct();
      $this->builder = $this->db->table('tb_kelas');
   }

   //input values
   public function inputValues()
   {
      return [
         'jilid' => inputPost('jilid'),
         'kategori' => inputPost('kategori'),
         'index_kelas' => inputPost('index_kelas'),
         'id_wali_kelas' => inputPost('id_wali_kelas'),
      ];
   }

   public function addKelas()
   {
      $data = $this->inputValues();
      return $this->builder->insert($data);
   }

   public function editKelas($id)
   {
      $kelas = $this->getKelas($id);
      if (!empty($kelas)) {
         $data = $this->inputValues();
         return $this->builder->where('id_kelas', $kelas->id_kelas)->update($data);
      }
      return false;
   }

   public function getDataKelas()
   {
      return $this->builder->select('tb_kelas.*, tb_guru.nama_guru as nama_wali_kelas, 
         CONCAT(tb_kelas.jilid, 
                IF(tb_kelas.kategori != "Campur", CONCAT(" ", tb_kelas.kategori), ""), 
                IF(tb_kelas.index_kelas != "", CONCAT(" ", tb_kelas.index_kelas), "")) as kelas')
         ->join('tb_guru', 'tb_kelas.id_wali_kelas = tb_guru.id_guru', 'left')
         ->orderBy('tb_kelas.jilid')
         ->orderBy('tb_kelas.kategori')
         ->orderBy('tb_kelas.index_kelas')
         ->get()->getResult('array');
   }

   public function getKelas($id)
   {
      return $this->builder->select('tb_kelas.*, tb_guru.nama_guru as nama_wali_kelas, 
         CONCAT(tb_kelas.jilid, 
                IF(tb_kelas.kategori != "Campur", CONCAT(" ", tb_kelas.kategori), ""), 
                IF(tb_kelas.index_kelas != "", CONCAT(" ", tb_kelas.index_kelas), "")) as kelas')
         ->join('tb_guru', 'tb_kelas.id_wali_kelas = tb_guru.id_guru', 'left')
         ->where('id_kelas', cleanNumber($id))
         ->get()->getRow();
   }

   public function getKelasByWali($id_guru)
   {
      return $this->builder->select('tb_kelas.*, tb_guru.nama_guru as nama_wali_kelas, 
         CONCAT(tb_kelas.jilid, 
                IF(tb_kelas.kategori != "Campur", CONCAT(" ", tb_kelas.kategori), ""), 
                IF(tb_kelas.index_kelas != "", CONCAT(" ", tb_kelas.index_kelas), "")) as kelas')
         ->join('tb_guru', 'tb_kelas.id_wali_kelas = tb_guru.id_guru', 'left')
         ->where('id_wali_kelas', cleanNumber($id_guru))
         ->get()->getRowArray();
   }

   public function getCategoryTree($categoryId, $categories)
   {
      $tree = array();
      $categoryId = cleanNumber($categoryId);
      if (!empty($categoryId)) {
         array_push($tree, $categoryId);
      }
      return $tree;
   }

   public function getKelasCountByJilid($jilidId)
   {
      return $this->builder->where('tb_kelas.id_kelas', $jilidId)->countAllResults();
   }

   public function deleteKelas($id)
   {
      $kelas = $this->getKelas($id);
      if (!empty($kelas)) {
         return $this->builder->where('id_kelas', $kelas->id_kelas)->delete();
      }
      return false;
   }


   public function getAllKelas()
   {
      return $this->select('tb_kelas.*, 
         CONCAT(tb_kelas.jilid, 
                IF(tb_kelas.kategori != "Campur", CONCAT(" ", tb_kelas.kategori), ""), 
                IF(tb_kelas.index_kelas != "", CONCAT(" ", tb_kelas.index_kelas), "")) as kelas')
         ->findAll();
   }

   public function getDistinctJilid()
   {
      return $this->builder->select('jilid')->distinct()->orderBy('jilid')->get()->getResultArray();
   }

   public function getDistinctIndexKelas()
   {
      return $this->builder->select('index_kelas')->distinct()->orderBy('index_kelas')->get()->getResultArray();
   }

   //generate CSV object
   public function generateCSVObject($filePath)
   {
      $array = array();
      $fields = array();
      $txtName = uniqid() . '.txt';
      $i = 0;
      $handle = fopen($filePath, 'r');
      if ($handle) {
         while (($row = fgetcsv($handle)) !== false) {
            if (empty($fields)) {
               $fields = $row;
               // Remove BOM from the first element if present
               if (isset($fields[0])) {
                  $fields[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $fields[0]);
               }
               // Trim all fields
               $fields = array_map('trim', $fields);
               continue;
            }
            foreach ($row as $k => $value) {
               if (isset($fields[$k])) {
                  $array[$i][$fields[$k]] = trim($value);
               }
            }
            $i++;
         }
         if (!feof($handle)) {
            return false;
         }
         fclose($handle);
         if (!empty($array)) {
            $txtFile = fopen(FCPATH . 'uploads/tmp/' . $txtName, 'w');
            fwrite($txtFile, serialize($array));
            fclose($txtFile);
            $obj = new \stdClass();
            $obj->numberOfItems = countItems($array);
            $obj->txtFileName = $txtName;
            @unlink($filePath);
            return $obj;
         }
      }
      return false;
   }

   //import csv item
   public function importCSVItem($txtFileName, $index)
   {
      $filePath = FCPATH . 'uploads/tmp/' . $txtFileName;
      $file = fopen($filePath, 'r');
      $content = fread($file, filesize($filePath));
      $array = @unserialize($content);
      if (!empty($array)) {
         $i = 1;
         foreach ($array as $item) {
            if ($i == $index) {
               $jilid = getCSVInputValue($item, 'jilid');
               $kategori = getCSVInputValue($item, 'kategori');
               $indexKelas = getCSVInputValue($item, 'index_kelas');

               if (!empty($jilid)) {
                  $insertData = [
                     'jilid' => $jilid,
                     'kategori' => $kategori ?: 'Campur',
                     'index_kelas' => $indexKelas,
                     'id_wali_kelas' => null, 
                  ];

                  $returnData = array_merge($insertData, ['kelas' => $jilid . ' ' . $kategori . ' ' . $indexKelas]);

                  // Check for duplicate
                  $exists = $this->builder->where('jilid', $jilid)
                     ->where('kategori', $insertData['kategori'])
                     ->where('index_kelas', $indexKelas)
                     ->countAllResults();

                  if ($exists > 0) {
                     return ['status' => 'duplicate', 'data' => $returnData];
                  }

                  $this->builder->insert($insertData);
                  return ['status' => 'success', 'data' => $returnData];
               }
            }
            $i++;
         }
      }
   }
}
