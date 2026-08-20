<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.services_banner_tag', 'Our Solutions');
        $this->migrator->add('site.services_banner_title', 'Overseas Recruitment & Mobility Services');
        $this->migrator->add('site.services_banner_desc', 'End-to-end solutions for foreign employer companies and Bangladeshi job seekers.');

        // Step-By-Step Deployment Workflow
        $this->migrator->add('site.services_workflow_subtitle', 'Step-By-Step Workflow');
        $this->migrator->add('site.services_workflow_title', 'How We Deploy Manpower');
        
        $this->migrator->add('site.services_workflow_step1_title', '01. Demand Receipt');
        $this->migrator->add('site.services_workflow_step1_desc', 'Employer posts visa demand order with embassy endorsement.');
        
        $this->migrator->add('site.services_workflow_step2_title', '02. Screening & Test');
        $this->migrator->add('site.services_workflow_step2_desc', 'Shortlisting and trade testing at certified technical workshops.');
        
        $this->migrator->add('site.services_workflow_step3_title', '03. Medical & Visa');
        $this->migrator->add('site.services_workflow_step3_desc', 'GAMCA medical checkup, MOFA visa stamping & BMET Smart Card.');
        
        $this->migrator->add('site.services_workflow_step4_title', '04. Flight Departure');
        $this->migrator->add('site.services_workflow_step4_desc', 'Pre-departure briefing, airline ticket issue & airport assistance.');
    }
};
