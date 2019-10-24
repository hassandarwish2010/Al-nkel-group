<?php

namespace App\Providers;

use App\Country;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Sheet;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		$url = '/';
		foreach ( request()->segments() as $key => $segment ) {
			if ( $key !== 0 ) {
				$url .= $segment . '/';
			}
		}

		View::share( 'url', $url );

//		View::share( 'countries', Country::all() );

		Validator::extend( 'valid_old_password', function ( $attribute, $value, $parameters, $validator ) {
			$user = User::find( $parameters[0] );

			return Hash::check( $value, $user->password );
		} );

		Sheet::macro( 'styleCells', function ( Sheet $sheet, string $cellRange, array $style ) {
			$sheet->getDelegate()->getStyle( $cellRange )->applyFromArray( $style );
		} );
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
