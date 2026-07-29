<?php

namespace App\Http\Traits;

trait GetUserData
{
    /**
     * Get user's all data.
     *
     * @param  object   $user
     * @param  boolean  $withId
     * @return object
     */
    private function getUserAllData($user, $withId = false)
    {
        // Get user categories
        $user->load([
            // 'categories' => function ($query) {
            //     $query->forUser()->active()->ordered()->where('parent_id',0);
            //     $query->with(['sub_categories' => function ($query) {
            //         $query->select('name','parent_id')->active();
            //     }]);
            // },
            'UserCategories' => function ($query) {
                $query->active()->where('category_user.is_default',1);
            },
            'city' => function ($query) {
                $query->active();

                $query->whereHas('state', function ($query) {
                    $query->active();

                    $query->whereHas('country', function ($query) {
                        $query->active();
                    });
                });
            },
            'education','state'
        ]);
        //--------------------

        // Get user city's state and country
        if (!is_null($user['city'])) {
            $user->city->load(['state' => function ($query) {
                $query->active();

                $query->with(['country' => function ($query) {
                    $query->active();
                }]);
            }]);
        }
        //----------------------------------

        if (count($user['UserCategories'])) {
           $user->load([
            'categories' => function ($query) {
                $query->forUser()->active()->ordered()->where('parent_id',0);
                $query->with(['sub_categories' => function ($query) {
                    $query->select('name','parent_id')->active();
                }]);
            },
            ]);
        }else{
            $user->load([
            'categories' => function ($query) {
                $query->forUser()->active()->ordered()->where('parent_id',0)->where('category_user.is_default',1);
                $query->with(['sub_categories' => function ($query) {
                    $query->select('name','parent_id')->active();
                }]);
            },
            ]);
        }

        if($user->cast_category == 'gen'){
            $user->castCategory = [
                'id' => "gen",
                'name' => 'General',
            ];
        }
        if($user->cast_category == 'obc'){
            $user->castCategory = [
                'id' => "obc",
                'name' => 'OBC',
            ];
        }
        if($user->cast_category == 'sc'){
            $user->castCategory = [
                'id' => "sc",
                'name' => 'SC',
            ];
        }
        if($user->cast_category == 'st'){
            $user->castCategory = [
                'id' => "st",
                'name' => 'ST',
            ];
        }

        // Hide id
        if (!$withId) {
            // $user->makeHidden('id');
        }
        //--------

        return $user;
    }
}
