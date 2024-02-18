<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class CrewPermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $crwPermissions = [
            ['menu' => 'Crew Management', 'subject' => 'Rank', 'name' => 'crw-rank-view'],
            ['menu' => 'Crew Management', 'subject' => 'Rank', 'name' => 'crw-rank-create'],
            ['menu' => 'Crew Management', 'subject' => 'Rank', 'name' => 'crw-rank-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Rank', 'name' => 'crw-rank-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Vessel Particular', 'name' => 'crw-vessel-particular-view'],
            ['menu' => 'Crew Management', 'subject' => 'Policy', 'name' => 'crw-policy-view'],
            ['menu' => 'Crew Management', 'subject' => 'Policy', 'name' => 'crw-policy-create'],
            ['menu' => 'Crew Management', 'subject' => 'Policy', 'name' => 'crw-policy-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Policy', 'name' => 'crw-policy-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Onboard Checklist', 'name' => 'crw-onboard-checklist-view'],
            ['menu' => 'Crew Management', 'subject' => 'Onboard Checklist', 'name' => 'crw-onboard-checklist-create'],
            ['menu' => 'Crew Management', 'subject' => 'Onboard Checklist', 'name' => 'crw-onboard-checklist-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Onboard Checklist', 'name' => 'crw-onboard-checklist-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Vessel Crew Manning', 'name' => 'crw-vessel-crew-manning-view'],
            ['menu' => 'Crew Management', 'subject' => 'Vessel Crew Manning', 'name' => 'crw-vessel-crew-manning-create'],
            ['menu' => 'Crew Management', 'subject' => 'Vessel Crew Manning', 'name' => 'crw-vessel-crew-manning-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Vessel Crew Manning', 'name' => 'crw-vessel-crew-manning-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Requisition', 'name' => 'crw-requisition-view'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Requisition', 'name' => 'crw-requisition-create'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Requisition', 'name' => 'crw-requisition-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Requisition', 'name' => 'crw-requisition-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Recruitment Approval', 'name' => 'crw-recruitment-approval-view'],
            ['menu' => 'Crew Management', 'subject' => 'Recruitment Approval', 'name' => 'crw-recruitment-approval-create'],
            ['menu' => 'Crew Management', 'subject' => 'Recruitment Approval', 'name' => 'crw-recruitment-approval-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Recruitment Approval', 'name' => 'crw-recruitment-approval-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Profile', 'name' => 'crw-agency-profile-view'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Profile', 'name' => 'crw-agency-profile-create'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Profile', 'name' => 'crw-agency-profile-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Profile', 'name' => 'crw-agency-profile-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Contract', 'name' => 'crw-agency-contract-view'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Contract', 'name' => 'crw-agency-contract-create'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Contract', 'name' => 'crw-agency-contract-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Contract', 'name' => 'crw-agency-contract-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Bill', 'name' => 'crw-agency-bill-view'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Bill', 'name' => 'crw-agency-bill-create'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Bill', 'name' => 'crw-agency-bill-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Agency Bill', 'name' => 'crw-agency-bill-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Profile', 'name' => 'crw-profile-view'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Profile', 'name' => 'crw-profile-create'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Profile', 'name' => 'crw-profile-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Profile', 'name' => 'crw-profile-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Document', 'name' => 'crw-document-view'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Document', 'name' => 'crw-document-create'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Document', 'name' => 'crw-document-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Document', 'name' => 'crw-document-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Document', 'name' => 'crw-document-renew'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Assignment', 'name' => 'crw-assignment-view'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Assignment', 'name' => 'crw-assignment-create'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Assignment', 'name' => 'crw-assignment-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Assignment', 'name' => 'crw-assignment-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Attendance', 'name' => 'crw-attendance-view'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Attendance', 'name' => 'crw-attendance-create'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Attendance', 'name' => 'crw-attendance-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Attendance', 'name' => 'crw-attendance-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Incident Record', 'name' => 'crw-incident-record-view'],
            ['menu' => 'Crew Management', 'subject' => 'Incident Record', 'name' => 'crw-incident-record-create'],
            ['menu' => 'Crew Management', 'subject' => 'Incident Record', 'name' => 'crw-incident-record-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Incident Record', 'name' => 'crw-incident-record-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Salary Structure', 'name' => 'crw-salary-structure-view'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Salary Structure', 'name' => 'crw-salary-structure-create'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Salary Structure', 'name' => 'crw-salary-structure-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Salary Structure', 'name' => 'crw-salary-structure-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Bank Accounts', 'name' => 'crw-bank-account-view'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Bank Accounts', 'name' => 'crw-bank-account-create'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Bank Accounts', 'name' => 'crw-bank-account-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Crew Bank Accounts', 'name' => 'crw-bank-account-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Salary List', 'name' => 'crw-salary-view'],
            ['menu' => 'Crew Management', 'subject' => 'Salary List', 'name' => 'crw-salary-create'],
            ['menu' => 'Crew Management', 'subject' => 'Salary List', 'name' => 'crw-salary-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Salary List', 'name' => 'crw-salary-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Appraisal Form', 'name' => 'crw-apprisal-form-view'],
            ['menu' => 'Crew Management', 'subject' => 'Appraisal Form', 'name' => 'crw-apprisal-form-create'],
            ['menu' => 'Crew Management', 'subject' => 'Appraisal Form', 'name' => 'crw-apprisal-form-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Appraisal Form', 'name' => 'crw-apprisal-form-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Appraisal Record', 'name' => 'crw-apprisal-record-view'],
            ['menu' => 'Crew Management', 'subject' => 'Appraisal Record', 'name' => 'crw-apprisal-record-create'],
            ['menu' => 'Crew Management', 'subject' => 'Appraisal Record', 'name' => 'crw-apprisal-record-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Appraisal Record', 'name' => 'crw-apprisal-record-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Rest Hour', 'name' => 'crw-rest-hour-view'],
            ['menu' => 'Crew Management', 'subject' => 'Rest Hour', 'name' => 'crw-rest-hour-create'],
            ['menu' => 'Crew Management', 'subject' => 'Rest Hour', 'name' => 'crw-rest-hour-edit'],
            ['menu' => 'Crew Management', 'subject' => 'Rest Hour', 'name' => 'crw-rest-hour-delete'],
            ['menu' => 'Crew Management', 'subject' => 'Rest Hour Record', 'name' => 'crw-rest-hour-record'],
        ];

        Permission::insert($crwPermissions);
    }
}
