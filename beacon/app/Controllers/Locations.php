<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Locations extends BaseController
{
    /**
     * Get provinces suggestions
     */
    public function getProvinces()
    {
        $query = $this->request->getGet('q') ?? '';
        $provinces = $this->loadProvinces();
        
        if (!empty($query)) {
            $query = strtolower($query);
            $provinces = array_filter($provinces, function($province) use ($query) {
                return stripos($province, $query) !== false;
            });
        }
        
        return $this->response->setJSON(array_values($provinces));
    }
    
    /**
     * Get cities/municipalities suggestions based on province
     */
    public function getCities()
    {
        $query = $this->request->getGet('q') ?? '';
        $province = $this->request->getGet('province') ?? '';
        
        $cities = $this->loadCities($province);
        
        if (!empty($query)) {
            $query = strtolower($query);
            $cities = array_filter($cities, function($city) use ($query) {
                return stripos($city, $query) !== false;
            });
        }
        
        return $this->response->setJSON(array_values($cities));
    }
    
    /**
     * Get barangays suggestions based on city/municipality
     */
    public function getBarangays()
    {
        $query = $this->request->getGet('q') ?? '';
        $city = $this->request->getGet('city') ?? '';
        
        $barangays = $this->loadBarangays($city);
        
        if (!empty($query)) {
            $query = strtolower($query);
            $barangays = array_filter($barangays, function($barangay) use ($query) {
                return stripos($barangay, $query) !== false;
            });
        }
        
        return $this->response->setJSON(array_values($barangays));
    }
    
    /**
     * Load all Philippine provinces
     */
    private function loadProvinces()
    {
        $jsonFile = APPPATH . 'Data/ph_locations.json';
        if (file_exists($jsonFile)) {
            $data = json_decode(file_get_contents($jsonFile), true);
            return array_keys($data);
        }
        return [];
    }
    
    /**
     * Load cities/municipalities for a province
     */
    private function loadCities($province)
    {
        $jsonFile = APPPATH . 'Data/ph_locations.json';
        if (file_exists($jsonFile) && !empty($province)) {
            $data = json_decode(file_get_contents($jsonFile), true);
            if (isset($data[$province])) {
                return array_keys($data[$province]);
            }
        }
        return [];
    }
    
    /**
     * Load barangays for a city/municipality
     */
    private function loadBarangays($city)
    {
        $jsonFile = APPPATH . 'Data/ph_locations.json';
        $province = $this->request->getGet('province') ?? '';
        
        if (file_exists($jsonFile) && !empty($city) && !empty($province)) {
            $data = json_decode(file_get_contents($jsonFile), true);
            if (isset($data[$province][$city])) {
                return $data[$province][$city];
            }
        }
        return [];
    }
}

