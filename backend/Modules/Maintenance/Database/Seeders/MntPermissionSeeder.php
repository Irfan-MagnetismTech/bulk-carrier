<?php

namespace Modules\Maintenance\Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class MntPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() : void
    {
        $mntPermissions = [
            ['menu' => 'Maintenance', 'subject' => 'Ship Department', 'name' => 'mnt-ship-department-view'],
            ['menu' => 'Maintenance', 'subject' => 'Ship Department', 'name' => 'mnt-ship-department-create'],
            ['menu' => 'Maintenance', 'subject' => 'Ship Department', 'name' => 'mnt-ship-department-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Ship Department', 'name' => 'mnt-ship-department-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Item Group', 'name' => 'mnt-item-group-view'],
            ['menu' => 'Maintenance', 'subject' => 'Item Group', 'name' => 'mnt-item-group-create'],
            ['menu' => 'Maintenance', 'subject' => 'Item Group', 'name' => 'mnt-item-group-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Item Group', 'name' => 'mnt-item-group-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Item', 'name' => 'mnt-item-view'],
            ['menu' => 'Maintenance', 'subject' => 'Item', 'name' => 'mnt-item-create'],
            ['menu' => 'Maintenance', 'subject' => 'Item', 'name' => 'mnt-item-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Item', 'name' => 'mnt-item-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Job List', 'name' => 'mnt-job-list-view'],
            ['menu' => 'Maintenance', 'subject' => 'Job List', 'name' => 'mnt-job-list-create'],
            ['menu' => 'Maintenance', 'subject' => 'Job List', 'name' => 'mnt-job-list-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Job List', 'name' => 'mnt-job-list-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Running Hour Entry', 'name' => 'mnt-running-hour-entry-view'],
            ['menu' => 'Maintenance', 'subject' => 'Running Hour Entry', 'name' => 'mnt-running-hour-entry-create'],
            ['menu' => 'Maintenance', 'subject' => 'Running Hour Entry', 'name' => 'mnt-running-hour-entry-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Work Requisitions Pending', 'name' => 'mnt-work-requisition-pending-view'],
            ['menu' => 'Maintenance', 'subject' => 'Work Requisitions Pending', 'name' => 'mnt-work-requisition-pending-create'],
            ['menu' => 'Maintenance', 'subject' => 'Work Requisitions Pending', 'name' => 'mnt-work-requisition-pending-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Work Requisitions Pending', 'name' => 'mnt-work-requisition-pending-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Work Requisitions WIP', 'name' => 'mnt-work-requisition-wip-view'],
            ['menu' => 'Maintenance', 'subject' => 'Work Requisitions WIP', 'name' => 'mnt-work-requisition-wip-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Work Requisitions Done', 'name' => 'mnt-work-requisition-done-view'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Function', 'name' => 'mnt-critical-function-view'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Function', 'name' => 'mnt-critical-function-create'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Function', 'name' => 'mnt-critical-function-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Function', 'name' => 'mnt-critical-function-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Item Category', 'name' => 'mnt-critical-item-category-view'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Item Category', 'name' => 'mnt-critical-item-category-create'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Item Category', 'name' => 'mnt-critical-item-category-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Item Category', 'name' => 'mnt-critical-item-category-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Item', 'name' => 'mnt-critical-item-view'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Item', 'name' => 'mnt-critical-item-create'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Item', 'name' => 'mnt-critical-item-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Item', 'name' => 'mnt-critical-item-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Vessel Item', 'name' => 'mnt-critical-vessel-item-view'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Vessel Item', 'name' => 'mnt-critical-vessel-item-create'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Vessel Item', 'name' => 'mnt-critical-vessel-item-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Vessel Item', 'name' => 'mnt-critical-vessel-item-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Spare List', 'name' => 'mnt-critical-spare-list-view'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Spare List', 'name' => 'mnt-critical-spare-list-create'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Spare List', 'name' => 'mnt-critical-spare-list-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Spare List', 'name' => 'mnt-critical-spare-list-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Item', 'name' => 'mnt-survey-item-view'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Item', 'name' => 'mnt-survey-item-create'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Item', 'name' => 'mnt-survey-item-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Item', 'name' => 'mnt-survey-item-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Type', 'name' => 'mnt-survey-type-view'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Type', 'name' => 'mnt-survey-type-create'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Type', 'name' => 'mnt-survey-type-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Type', 'name' => 'mnt-survey-type-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Survey', 'name' => 'mnt-survey-view'],
            ['menu' => 'Maintenance', 'subject' => 'Survey', 'name' => 'mnt-survey-create'],
            ['menu' => 'Maintenance', 'subject' => 'Survey', 'name' => 'mnt-survey-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Survey', 'name' => 'mnt-survey-delete'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Entry', 'name' => 'mnt-survey-entry-view'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Entry', 'name' => 'mnt-survey-entry-create'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Entry', 'name' => 'mnt-survey-entry-edit'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Entry', 'name' => 'mnt-survey-entry-delete'],
            ['menu' => 'Maintenance', 'subject' => 'All Jobs Report', 'name' => 'mnt-all-jobs-report'],
            ['menu' => 'Maintenance', 'subject' => 'Upcoming Jobs Report', 'name' => 'mnt-upcoming-jobs-report'],
            ['menu' => 'Maintenance', 'subject' => 'Overdue Jobs Report', 'name' => 'mnt-overdue-jobs-report'],
            ['menu' => 'Maintenance', 'subject' => 'Critical Vessel Functions Report', 'name' => 'mnt-critical-vessel-functions-report'],
            ['menu' => 'Maintenance', 'subject' => 'Survey Entries Report', 'name' => 'mnt-survey-entries-report'],
        ];

        
        Permission::insert($mntPermissions);
    }
}
