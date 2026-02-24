<?php

namespace App\Providers;

use App\Models\Store;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class BusinessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share business-related variables with all views
        View::composer('*', function ($view) {
            $businessType = business_type() ?? 'clinic';
            $currentStore = current_store();
            $availableStores = collect();
            $businessTypeFilter = null;

            if (request()->hasSession()) {
                $filter = request()->session()->get('business_type_filter');
                $businessTypeFilter = is_string($filter) && $filter !== '' ? $filter : null;
            }

            if (auth()->check()) {
                $user = auth()->user();

                try {
                    $query = Store::query()->orderBy('name');

                    if ($businessTypeFilter) {
                        $query->where('business_type', $businessTypeFilter);
                    }

                    if ($user->isAdmin()) {
                        $availableStores = $query->get();
                    } elseif ($user->isOwner()) {
                        $availableStores = $query->where('owner_id', $user->id)->get();
                    }
                } catch (\Exception $e) {
                    $availableStores = collect();
                }
            }

            // Staff label mapping based on business type
            $staffLabels = [
                'clinic' => [
                    'singular' => __('appointment.beautician'),
                    'plural' => __('appointment.all_beauticians'),
                ],
                'salon' => [
                    'singular' => __('appointment.hairstylist'),
                    'plural' => __('appointment.all_hairstylists'),
                ],
                'barbershop' => [
                    'singular' => __('appointment.barber'),
                    'plural' => __('appointment.all_barbers'),
                ],
                'gym' => [
                    'singular' => __('appointment.trainer'),
                    'plural' => __('appointment.all_trainers'),
                ],
            ];

            $currentLabels = $staffLabels[$businessType] ?? $staffLabels['clinic'];

            // Get theme configuration
            $theme = business_theme();

            // Get business name
            $businessName = business_label('name');

            // Theme CSS classes for views
            $themeClasses = [
                'primary' => 'primary',
                'button' => 'bg-primary-600 hover:bg-primary-700',
                'buttonOutline' => 'border-primary-600 text-primary-600 hover:bg-primary-50',
                'buttonLight' => 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400',
                'link' => 'text-primary-600 hover:text-primary-700',
                'link_hover' => 'hover:text-primary-700',
                'linkDark' => 'text-primary-700 hover:text-primary-800',
                'badgeBg' => 'bg-primary-100',
                'badgeText' => 'text-primary-700',
                'badge' => 'bg-primary-100 text-primary-700',
                'accent' => 'text-primary-600',
                'accentBg' => 'bg-primary-600',
                'ring' => 'focus:ring-primary-500/20 focus:border-primary-400',
                'gradient' => 'from-primary-400 to-primary-500',
                'checkbox' => 'text-primary-600',
                'radio' => 'text-primary-600',
                'bgLight' => 'bg-primary-50',
                'bgMedium' => 'bg-primary-100',
                'iconColor' => 'text-primary-600',
                'borderHover' => 'border-primary-200',
                'borderActive' => 'border-primary-500',
                'shadowLight' => 'shadow-primary-200/50',
                'shadowMedium' => 'shadow-primary-300/50',
            ];

            $tc = $themeClasses;

            $view->with([
                'businessType' => $businessType,
                'businessName' => $businessName,
                'currentStore' => $currentStore,
                'availableStores' => $availableStores,
                'businessTypeFilter' => $businessTypeFilter,
                'staffLabel' => $currentLabels['singular'],
                'allStaffLabel' => $currentLabels['plural'],
                'theme' => $theme,
                // Theme classes shortcuts
                'tc' => (object) $tc,
                // Legacy theme variables for backward compatibility
                'themeButton' => $tc['button'],
                'themeButtonOutline' => $tc['buttonOutline'],
                'themeLink' => $tc['link'],
                'themeLinkDark' => $tc['linkDark'],
                'themeBadgeBg' => $tc['badgeBg'],
                'themeBadgeText' => $tc['badgeText'],
                'themeBadge' => $tc['badge'],
                'themeAccent' => $tc['accent'],
                'themeAccentBg' => $tc['accentBg'],
                'themeRing' => $tc['ring'],
                'themeGradient' => $tc['gradient'],
                'themeCheckbox' => $tc['checkbox'],
                'themePrimary' => $tc['primary'],
            ]);
        });
    }
}
