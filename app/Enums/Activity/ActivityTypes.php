<?php

namespace App\Enums\Activity;

use App\Traits\EnumValues;

enum ActivityTypes: string
{
    case CampusRepresentatives = 'Campus Representatives';
    case CareerDevelopmentActivities = 'Career Development Activities';
    case ExtraCurricularActivities = 'Extra-curricular Activities';
    case LanguageActivities = 'Language Activities';
    case OtherAchievements = 'Other Achievements';
    case PersonalDevelopmentActivities = 'Personal Development Activities';
    case PhysicalEducationAndSports = 'Physical Education & Sports';
    case ProfessionalQualifications = 'Professional Qualifications';
    case StudentGroups = 'Student Groups';
    case StudentOrganizations = 'Student Organizations';
    case VolunteerServices = 'Volunteer Services';

    use EnumValues;
}
