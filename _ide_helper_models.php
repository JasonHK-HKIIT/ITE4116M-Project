<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string|null $activity_type
 * @property string|null $activity_code
 * @property int $campus_id
 * @property string|null $instructor
 * @property string|null $responsible_staff
 * @property \Illuminate\Support\Carbon $execution_from
 * @property \Illuminate\Support\Carbon $execution_to
 * @property \Illuminate\Support\Carbon|null $time_slot_from_date
 * @property \Illuminate\Support\Carbon|null $time_slot_from_time
 * @property \Illuminate\Support\Carbon|null $time_slot_to_date
 * @property \Illuminate\Support\Carbon|null $time_slot_to_time
 * @property numeric $duration_hours
 * @property bool $swpd_programme
 * @property string|null $venue
 * @property int $capacity
 * @property int $registered
 * @property numeric $total_amount
 * @property numeric $included_deposit
 * @property string|null $attachment
 * @property \App\Enums\Activity\Disciplines|null $discipline
 * @property \App\Enums\Activity\Attributes|null $attribute
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ActivityTranslation> $activityTranslation
 * @property-read int|null $activity_translation_count
 * @property-read \App\Models\Campus $campus
 * @property-read mixed $has_vacancy
 * @property-read \App\Models\ActivityTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ActivityTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\ActivityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity translated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereActivityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereActivityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereDiscipline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereDurationHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereExecutionFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereExecutionTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereIncludedDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereInstructor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereRegistered($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereResponsibleStaff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereSwpdProgramme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereTimeSlotFromDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereTimeSlotFromTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereTimeSlotToDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereTimeSlotToTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereVenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity withTranslation(?string $locale = null)
 */
	class Activity extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $activity_id
 * @property string $locale
 * @property string $title
 * @property string|null $description
 * @property string|null $venue_remark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityTranslation whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityTranslation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityTranslation whereVenueRemark($value)
 */
	class ActivityTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $class_id
 * @property int|null $student_id
 * @property int|null $institute_id
 * @property \App\Enums\CalendarEventType $type
 * @property string $title
 * @property string|null $description
 * @property string|null $location
 * @property string|null $instructor
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon $end_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ClassModel|null $classModel
 * @property-read \App\Models\Institute|null $institute
 * @property-read \App\Models\Student|null $student
 * @method static \Database\Factories\CalendarEventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereInstituteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereInstructor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarEvent whereUpdatedAt($value)
 */
	class CalendarEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CampusTranslation> $campusTranslation
 * @property-read int|null $campus_translation_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Institute> $institutes
 * @property-read int|null $institutes_count
 * @property-read \App\Models\CampusTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CampusTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\CampusFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus translated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus withTranslation(?string $locale = null)
 */
	class Campus extends \Eloquent implements \Astrotomic\Translatable\Contracts\Translatable {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $campus_id
 * @property string $locale
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampusTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampusTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampusTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampusTranslation whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampusTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampusTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampusTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampusTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampusTranslation whereUpdatedAt($value)
 */
	class CampusTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $academic_year
 * @property int $institute_id
 * @property int $campus_id
 * @property int $programme_id
 * @property string $class_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campus $campus
 * @property-read \App\Models\Institute $institute
 * @property-read \App\Models\Programme $programme
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereAcademicYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereClassCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereInstituteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereProgrammeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereUpdatedAt($value)
 */
	class ClassModel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Campus> $campuses
 * @property-read int|null $campuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InstituteTranslation> $instituteTranslation
 * @property-read int|null $institute_translation_count
 * @property-read \App\Models\InstituteTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InstituteTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\InstituteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute translated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institute withTranslation(?string $locale = null)
 */
	class Institute extends \Eloquent implements \Astrotomic\Translatable\Contracts\Translatable {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteCampus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteCampus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteCampus query()
 */
	class InstituteCampus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $institute_id
 * @property string $locale
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteTranslation whereInstituteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstituteTranslation whereUpdatedAt($value)
 */
	class InstituteTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $institute_id
 * @property string $module_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Institute|null $institute
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ModuleTranslation> $moduleTranslation
 * @property-read int|null $module_translation_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Programme> $programmes
 * @property-read int|null $programmes_count
 * @property-read \App\Models\ModuleTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ModuleTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\ModuleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module translated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereInstituteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereModuleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module withTranslation(?string $locale = null)
 */
	class Module extends \Eloquent implements \Astrotomic\Translatable\Contracts\Translatable {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $module_id
 * @property string $locale
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleTranslation whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleTranslation whereUpdatedAt($value)
 */
	class ModuleTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $slug
 * @property \App\Enums\NewsArticleStatus $status
 * @property \Illuminate\Support\Carbon|null $published_on
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NewsArticleTranslation> $newsArticleTranslation
 * @property-read int|null $news_article_translation_count
 * @property-read \App\Models\NewsArticleTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NewsArticleTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\NewsArticleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle translated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle wherePublishedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticle withTranslation(?string $locale = null)
 */
	class NewsArticle extends \Eloquent implements \Astrotomic\Translatable\Contracts\Translatable, \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $news_article_id
 * @property string $locale
 * @property string $title
 * @property string|array $content
 * @method static \Database\Factories\NewsArticleTranslationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticleTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticleTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticleTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticleTranslation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticleTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticleTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticleTranslation whereNewsArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsArticleTranslation whereTitle($value)
 */
	class NewsArticleTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $institute_id
 * @property string $programme_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Institute|null $institute
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Module> $modules
 * @property-read int|null $modules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProgrammeTranslation> $programmeTranslation
 * @property-read int|null $programme_translation_count
 * @property-read \App\Models\ProgrammeTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProgrammeTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\ProgrammeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme translated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme whereInstituteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme whereProgrammeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Programme withTranslation(?string $locale = null)
 */
	class Programme extends \Eloquent implements \Astrotomic\Translatable\Contracts\Translatable {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $programme_id
 * @property int $module_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ProgrammeModuleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeModule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeModule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeModule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeModule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeModule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeModule whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeModule whereProgrammeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeModule whereUpdatedAt($value)
 */
	class ProgrammeModule extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $programme_id
 * @property string $locale
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeTranslation whereProgrammeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProgrammeTranslation whereUpdatedAt($value)
 */
	class ProgrammeTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceTranslation> $resourceTranslation
 * @property-read int|null $resource_translation_count
 * @property-read \App\Models\ResourceTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResourceTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\ResourceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource translated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource withTranslation(?string $locale = null)
 */
	class Resource extends \Eloquent implements \Astrotomic\Translatable\Contracts\Translatable, \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $resource_id
 * @property string $locale
 * @property string $title
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Database\Factories\ResourceTranslationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceTranslation whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceTranslation whereTitle($value)
 */
	class ResourceTranslation extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $institute_id
 * @property int $campus_id
 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $nationality
 * @property string|null $mother_tongue
 * @property string|null $tel_no
 * @property string|null $mobile_no
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campus|null $campus
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassModel> $classes
 * @property-read int|null $classes_count
 * @property-read \App\Models\Institute|null $institute
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Module> $modules
 * @property-read int|null $modules_count
 * @property string $student_id
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\StudentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereInstituteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereMobileNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereMotherTongue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereTelNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUserId($value)
 */
	class Student extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $student_id
 * @property int $class_id
 * @method static \Database\Factories\StudentClassFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentClass query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentClass whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentClass whereStudentId($value)
 */
	class StudentClass extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string|null $remember_token
 * @property \App\Enums\Role $role
 * @property string $family_name
 * @property string $given_name
 * @property string|null $chinese_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $avatar
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserPermission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Student|null $student
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereChineseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFamilyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGivenName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\Authenticatable, \Illuminate\Contracts\Auth\Access\Authorizable, \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property \App\Enums\Permission $permission
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPermission query()
 */
	class UserPermission extends \Eloquent {}
}

