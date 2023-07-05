<?php

namespace App\Http\Middleware\LMS;

use App\Http\Controllers\LMS\Web\CartManagerController;
use App\Mixins\Financial\MultiCurrency;
use App\Models\LMS\Cart;
use App\Models\LMS\Currency;
use App\Models\LMS\FloatingBar;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Share
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (auth()->guard('lms_user')->check()) {
            $user = auth()->guard('lms_user')->user();
            view()->share('authUser', $user);

            if (!$user->isAdmin()) {

                $unReadNotifications = $user->getUnReadNotifications();

                view()->share('unReadNotifications', $unReadNotifications);
            }
        }

        $cartManagerController = new CartManagerController();
        $carts = $cartManagerController->getCarts();
        $totalCartsPrice = Cart::getCartsTotalPrice($carts);

        view()->share('userCarts', $carts);
        view()->share('totalCartsPrice', $totalCartsPrice);

        $generalSettings = getGeneralSettings();
        view()->share('generalSettings', $generalSettings);
        $currency = currencySign();
        view()->share('currency', $currency);

        if (getFinancialCurrencySettings('multi_currency')) {
            $multiCurrency = new MultiCurrency();
            $currencies = $multiCurrency->getCurrencies();

            if ($currencies->isNotEmpty()) {
                view()->share('currencies', $currencies);
            }
        }

        // locale config
        if(domain_info('shop_type')==2)
        {
            if (!Session::has('locale')) {
                Session::put('locale', mb_strtolower(getDefaultLocale()));
            }
            App::setLocale(session('locale'));
        }

        view()->share('categories', \App\Models\LMS\Category::getCategories());
        view()->share('navbarPages', getNavbarLinks());

        $floatingBar = FloatingBar::getFloatingBar($request);
        view()->share('floatingBar', $floatingBar);

        return $next($request);
    }
}
