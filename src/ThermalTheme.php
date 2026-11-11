<?php

declare(strict_types=1);

namespace Forumify\ThermalTheme;

use Forumify\ForumifyTheme;
use Forumify\Plugin\PluginMetadata;
use Forumify\Plugin\ThemeConfig;
use Forumify\Plugin\ThemeVar;
use Forumify\Plugin\ThemeVarType;

/**
 * Thermal targeting optics. The ironbow palette of a thermal camera runs through the
 * whole theme: panels carry a heat edge, warm up under the cursor, and every poster
 * leaves a heat signature behind their avatar. Dark mode is the classic ironbow view,
 * light mode is the camera's black-hot greyscale.
 *
 * forumify's own variables keep their meaning, only their defaults change, so the admin
 * panel and every plugin pick up the palette. The thermal-* variables drive the heat
 * scale, reticle and scanlines in public/style.css and can be tuned under
 * Admin → Settings → Themes.
 */
class ThermalTheme extends ForumifyTheme
{
    /**
     * New defaults for forumify's own variables, as [light, dark].
     */
    private const OVERRIDES = [
        'c-primary' => ['#b3361b', '#ff9a2e'],
        'c-primary-accent' => ['#d94a1f', '#ffc14d'],
        'c-primary-text' => ['#ffffff', '#120a1f'],
        'c-secondary' => ['#6d1a8a', '#b31b6d'],
        'c-secondary-text' => ['#ffffff', '#ffffff'],
        'c-call-to-action' => ['#e8a10c', '#fde96a'],
        'c-call-to-action-accent' => ['#f2b632', '#fff3a6'],
        'c-call-to-action-text' => ['#16121d', '#120a1f'],
        'c-text-primary' => ['#16121d', '#f3ecff'],
        'c-text-secondary' => ['#5d586a', '#a497c4'],
        'c-success' => ['#dcefe3', '#16302a'],
        'c-success-text' => ['#14593b', '#8ff0c4'],
        'c-info' => ['#e3e1f4', '#221a46'],
        'c-info-text' => ['#3b2a8f', '#c9bfff'],
        'c-warning' => ['#fbe9cc', '#3a2410'],
        'c-warning-text' => ['#7a3d00', '#ffc98a'],
        'c-error' => ['#f8dcdc', '#3d1220'],
        'c-error-text' => ['#8f1d2c', '#ff9fb1'],
        'c-elevation-0' => ['#e4e2e8', '#120a1f'],
        'c-elevation-1' => ['#f6f5f8', '#1a1029'],
        'c-elevation-2' => ['#ecebf0', '#221636'],
        'c-elevation-3' => ['#e0dee6', '#2b1c44'],
        'c-elevation-4' => ['#d6d3de', '#342352'],
        'c-elevation-5' => ['#cbc8d5', '#3d2a60'],
        'c-hover' => ['rgba(179, 54, 27, 0.06)', 'rgba(255, 154, 46, 0.07)'],
        'border-color' => ['rgba(22, 18, 29, 0.14)', 'rgba(243, 236, 255, 0.10)'],
        'border-radius' => ['6px', '6px'],
        'button-border-radius' => ['4px', '4px'],
        'font-size' => ['16px', '16px'],
    ];

    public function getPluginMetadata(): PluginMetadata
    {
        return new PluginMetadata(
            'Thermal',
            'forumify',
            'Thermal targeting optics: ironbow heat gradients, a scope reticle and panels that heat up under your cursor.',
            'https://forumify.net',
        );
    }

    public function getThemeConfig(): ThemeConfig
    {
        $vars = array_map(static function (ThemeVar $var): ThemeVar {
            if (!isset(self::OVERRIDES[$var->key])) {
                return $var;
            }

            [$light, $dark] = self::OVERRIDES[$var->key];
            return new ThemeVar($var->key, $var->label, $var->type, $light, $dark, $var->help);
        }, parent::getThemeConfig()->vars);

        return new ThemeConfig(true, [
            ...$vars,
            new ThemeVar(
                'color-scheme',
                'Color Scheme',
                ThemeVarType::String,
                'light',
                'dark',
                'Tells the browser whether native controls and scrollbars should be light or dark.',
            ),
            new ThemeVar('thermal-cold', 'Heat Scale: Cold', ThemeVarType::Color, '#2a0b5e', '#2a0e5c', 'The heat scale runs from cold to white hot.'),
            new ThemeVar('thermal-cool', 'Heat Scale: Cool', ThemeVarType::Color, '#7a1fa2', '#7a1aa8'),
            new ThemeVar('thermal-warm', 'Heat Scale: Warm', ThemeVarType::Color, '#d42a5b', '#d42a6b'),
            new ThemeVar('thermal-hot', 'Heat Scale: Hot', ThemeVarType::Color, '#f26a1b', '#f2581e'),
            new ThemeVar('thermal-burn', 'Heat Scale: Burning', ThemeVarType::Color, '#ffb21f', '#fca311'),
            new ThemeVar('thermal-white', 'Heat Scale: White Hot', ThemeVarType::Color, '#ffe98a', '#fff1a8'),
            new ThemeVar('thermal-glow', 'Heat Glow', ThemeVarType::Color, 'rgba(242, 106, 27, 0.30)', 'rgba(242, 88, 30, 0.45)', 'Glow around panels and buttons that are heating up.'),
            new ThemeVar('thermal-reticle', 'Reticle', ThemeVarType::Color, 'rgba(22, 18, 29, 0.09)', 'rgba(243, 236, 255, 0.07)', 'Colour of the scope reticle behind the page. Use transparent to hide it.'),
            new ThemeVar('thermal-scanlines', 'Scanlines', ThemeVarType::String, '0.35', '0.8', 'Opacity of the sensor scanlines, between 0 and 1.'),
            new ThemeVar('thermal-font', 'Font Family', ThemeVarType::String, "'Saira', sans-serif"),
        ]);
    }

    /**
     * Stylesheets loaded on every page while the theme is active, relative to public/.
     *
     * @return array<string>
     */
    public function getStylesheets(): array
    {
        return ['style.css'];
    }
}
