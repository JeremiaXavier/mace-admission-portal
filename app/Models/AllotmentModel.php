<?php

namespace App\Models;

use CodeIgniter\Model;

class AllotmentModel extends Model
{
    protected $table = 'spot_registrations';
    protected $primaryKey = 'id';
    
    // We only use this model for Read Queries in the Admin dashboard.
    // Insertions are done via bare-metal Query Builder in RegistrationController.
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['allotted_course']; 
    
    public function getByCategory(string $category, int $limit, int $offset, string $sortBy = 'entrance_rank', string $sortDir = 'ASC')
    {
        $builder = $this->select('id, entrance_rank, entrance_roll_no, full_name, mobile_no, eligible_category, time_of_reporting, option_1, option_2, option_3, option_4, option_5, option_6, option_7')
                        ->where('status', 'submitted');

        if (!empty($category) && $category !== 'SM') {
            $builder->where('eligible_category', $category);
        }
        
        return $builder->orderBy($sortBy, $sortDir)
                       ->findAll($limit, $offset);
    }
    
    public function getByBranch(string $branch, string $category = '')
    {
        $builder = $this->select('id, entrance_rank, entrance_roll_no, full_name, mobile_no, eligible_category, allotted_course, option_1, option_2, option_3, option_4, option_5, option_6, option_7')
                        ->where('status', 'submitted');
                        
        if (!empty($category) && $category !== 'SM') {
            $builder->where('eligible_category', $category);
        }
        
        return $builder->groupStart()
                        ->where('option_1', $branch)
                        ->orWhere('option_2', $branch)
                        ->orWhere('option_3', $branch)
                        ->orWhere('option_4', $branch)
                        ->orWhere('option_5', $branch)
                        ->orWhere('option_6', $branch)
                        ->orWhere('option_7', $branch)
                    ->groupEnd()
                    ->orderBy('entrance_rank', 'ASC')
                    ->findAll();
    }
    
    public function getAll(int $limit, int $offset, string $sortBy = 'entrance_rank', string $sortDir = 'ASC')
    {
        return $this->select('id, entrance_rank, entrance_roll_no, full_name, mobile_no, eligible_category, time_of_reporting, option_1, option_2, option_3, option_4, option_5, option_6, option_7')
                    ->where('status', 'submitted')
                    ->orderBy($sortBy, $sortDir)
                    ->findAll($limit, $offset);
    }
    
    public function searchByRollNo(string $rollNo)
    {
        return $this->select('id, entrance_rank, entrance_roll_no, full_name, mobile_no, eligible_category, allotted_course, time_of_reporting, option_1, option_2, option_3, option_4, option_5, option_6, option_7')
                    ->where('entrance_roll_no', $rollNo)
                    ->findAll();
    }
    
    public function searchByRank(int $rank)
    {
        return $this->select('id, entrance_rank, entrance_roll_no, full_name, mobile_no, eligible_category, allotted_course, time_of_reporting, option_1, option_2, option_3, option_4, option_5, option_6, option_7')
                    ->where('entrance_rank', $rank)
                    ->orderBy('registered_at', 'ASC')
                    ->findAll();
    }
}
