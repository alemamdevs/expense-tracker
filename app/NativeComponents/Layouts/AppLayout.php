<?php

namespace App\NativeComponents\Layouts;

use App\Icons\Android;
use App\Icons\Ios;
use Native\Mobile\Edge\Layouts\Builders\Tab;
use Native\Mobile\Edge\Layouts\Builders\TabBar;
use Native\Mobile\Edge\Layouts\NativeLayout;
use Native\Mobile\Edge\NativeComponent;
use Native\Mobile\UI\Builders\FloatingOverlay;
use Native\Mobile\UI\Concerns\HasFloatingOverlay;

class AppLayout extends NativeLayout
{
    use HasFloatingOverlay;

    /**
     * Render the tab bar with real native chrome (TabView / Scaffold).
     */
    public function usesNativeChrome(): bool
    {
        return true;
    }

    /**
     * The four primary tabs.
     */
    public function tabBar(NativeComponent $screen): ?TabBar
    {
        return TabBar::make()
            ->add(Tab::link('Home', '/', ios: Ios::House, android: Android::Home))
            ->add(Tab::link('Transactions', '/transactions', ios: Ios::ListBullet, android: Android::ReceiptLong))
            ->add(Tab::link('Statistics', '/statistics', ios: Ios::ChartBar, android: Android::BarChart))
            ->add(Tab::link('Settings', '/settings', ios: Ios::Gearshape, android: Android::Settings));
    }

    /**
     * Center floating action button for adding a transaction.
     */
    public function floatingOverlay(NativeComponent $screen): ?FloatingOverlay
    {
        return FloatingOverlay::make(view('native.partials.add-fab'));
    }
}
