<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class index {


    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
		var_dump(__file__);

 
    }

}