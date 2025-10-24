<?php

namespace App\Providers;

use App\Models\FormField;
use App\Models\Frontend\FrontCustomPageNavbar;
use App\Models\Frontend\FrontEvent;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\School;
use App\Models\User;
use App\Services\CachingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        $cache = app(CachingService::class);

        $galleries = '';
        $teachers = '';
        $schoolSettings = '';
        $school = '';
    
        
        
       
            $teachers = User::role('Teacher#1')->select('id','first_name','last_name','image')->with('staff')->get();
          //  dd($teachers );
            $schoolSettings = $cache->getSchoolSettings('*');
          //  dd($schoolSettings);
            if (isset($schoolSettings['our_mission_points'])) {
                $schoolSettings['our_mission_points'] = explode(",",$schoolSettings['our_mission_points']);    
            }
            $galleries = Gallery::with('file')->withCount(['file' => function($q) {
                $q->where('type',2);
            }])->get();
        
            $events = FrontEvent::orderBy('id', 'DESC')->where('status','publish')->select(['title', 'id','slug','images'])->get();

        

        /*** Header File ***/
        View::composer('layouts.header', static function (\Illuminate\View\View $view) use ($cache) {
            $view->with('languages', $cache->getLanguages());

            if (!empty(Auth::user()->school_id)) {
                $view->with('sessionYear', $cache->getDefaultSessionYear());
                $view->with('schoolSettings', $cache->getSchoolSettings());
                $view->with('semester', $cache->getDefaultSemesterData());
            }
        });

        /*** Include File ***/
        View::composer('layouts.include', static function (\Illuminate\View\View $view) use ($cache) {
          
                $view->with('schoolSettings', $cache->getSchoolSettings());
            
        });
   
        /*** Email  ***/

  

        /*** Footer File ***/
        View::composer('layouts.footer_js', static function (\Illuminate\View\View $view) use ($cache) {
           
                $view->with('schoolSettings', $cache->getSchoolSettings());
            
        });


        /*** School website ***/
        View::composer('frontend.school-website.*', static function (\Illuminate\View\View $view) use ($cache, $galleries, $teachers, $schoolSettings) {
            // if ($school) {
            //     $schoolSettings = $cache->getSchoolSettings('*',$school->id);
            //     if (isset($schoolSettings['our_mission_points'])) {
            //         $schoolSettings['our_mission_points'] = explode(",",$schoolSettings['our_mission_points']);    
            //     }
            //     $view->with('schoolSettings', $schoolSettings);
            // }
            $events = FrontEvent::orderBy('id', 'DESC')->where('status','publish')->select(['title', 'id','slug','images'])->get();
            $front_navbar=FrontCustomPageNavbar::where('status','publish')->with('custom_pages')->get();
          //  dd($front_navbar);
          $extraFields = FormField::orderBy('rank')->get();

            $view->with('schoolSettings', $schoolSettings);
            $view->with('teachers', $teachers);
            $view->with('galleries', $galleries);
            $view->with('events', $events);
            $view->with('front_navbar', $front_navbar);
            $view->with('extraFields', $extraFields);
        });
    }
}
