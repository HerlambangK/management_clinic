@php
/**
 * Theme Classes Partial
 * Include this at the top of blade files to get consistent theme classes
 * Usage: @include('components.theme-classes')
 */

$currentTheme = (array) ($tc ?? []);

// Extract to individual variables for easy use
$themeButton = $currentTheme['button'] ?? 'bg-primary-600 hover:bg-primary-700';
$themeButtonOutline = $currentTheme['buttonOutline'] ?? 'border-primary-600 text-primary-600 hover:bg-primary-50';
$themeLink = $currentTheme['link'] ?? 'text-primary-600 hover:text-primary-700';
$themeLinkDark = $currentTheme['linkDark'] ?? 'text-primary-700 hover:text-primary-800';
$themeBadgeBg = $currentTheme['badgeBg'] ?? 'bg-primary-100';
$themeBadgeText = $currentTheme['badgeText'] ?? 'text-primary-700';
$themeBadge = $currentTheme['badge'] ?? 'bg-primary-100 text-primary-700';
$themeAccent = $currentTheme['accent'] ?? 'text-primary-600';
$themeAccentBg = $currentTheme['accentBg'] ?? 'bg-primary-600';
$themeRing = $currentTheme['ring'] ?? 'focus:ring-primary-500/20 focus:border-primary-400';
$themeGradient = $currentTheme['gradient'] ?? 'from-primary-400 to-primary-500';
@endphp
