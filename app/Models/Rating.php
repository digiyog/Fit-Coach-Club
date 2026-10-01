<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rating extends Model
{
    protected $table = "ratings";
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Helper to apply common search and filters
     */
    public static function applyFiltersQuery($query, $search = null, $filter = array())
    {
        if (!empty($filter['user_id'])) {
            $query->where("ratings.user_id", $filter['user_id']);
        }

        if (!empty($filter['rating_filter'])) {
            if ($filter['rating_filter'] === 'unrated') {
                $query->where(function($q) {
                    $q->whereNull('ratings.rating')->orWhere('ratings.rating', '<=', 0);
                });
            } else {
                $query->where('ratings.rating', intval($filter['rating_filter']));
            }
        }

        if (!empty($filter['message_filter'])) {
            if ($filter['message_filter'] === 'with_message') {
                $query->whereNotNull('ratings.message')->whereRaw("TRIM(ratings.message) != ''");
            } elseif ($filter['message_filter'] === 'without_message') {
                $query->where(function($q) {
                    $q->whereNull('ratings.message')->orWhereRaw("TRIM(ratings.message) = ''");
                });
            }
        }

        if (!empty($filter['filter_date_range'])) {
            $rangeStr = $filter['filter_date_range'];
            if (strpos($rangeStr, 'to') !== false) {
                $dateRange = explode('to', $rangeStr);
            } elseif (strpos($rangeStr, '/') !== false) {
                $dateRange = explode('/', $rangeStr);
            } elseif (strpos($rangeStr, ' - ') !== false) {
                $dateRange = explode(' - ', $rangeStr);
            } else {
                $dateRange = [];
            }

            if (count($dateRange) == 2) {
                $query->whereDate('ratings.created_at', '>=', Carbon::parse(trim($dateRange[0])));
                $query->whereDate('ratings.created_at', '<=', Carbon::parse(trim($dateRange[1])));
            }
        }

        if (!empty($search)) {
            $search = strtolower(trim($search));
            $query->where(function($q) use ($search) {
                $q->whereRaw('lower(users.name) LIKE ?', ['%' . $search . '%'])
                  ->orWhereRaw('lower(ratings.message) LIKE ?', ['%' . $search . '%']);
            });
        }

        return $query;
    }

    /**
     * Get Reviews list
     */
    public function scopeGetReviews($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $ratings = Rating::select('ratings.id', 'users.name', 'ratings.rating', 'ratings.message', 'ratings.created_at');
        
        $ratings->leftJoin('users', function($join){
            $join->on('users.id', '=', 'ratings.user_id');
        });

        // Apply filters
        self::applyFiltersQuery($ratings, $search, $filter);
        
        // Table columns sort conditions
        // Columns: 0: checkbox, 1: name, 2: rating, 3: message, 4: created_at
        if (!empty($sort) && isset($sort['column'])) {
            $arr_fields = array("", "users.name", "ratings.rating", "ratings.message", "ratings.created_at");
            $colIdx = intval($sort['column']);
            if (isset($arr_fields[$colIdx]) && !empty($arr_fields[$colIdx])) {
                $dir = $sort['dir'] ?? 'DESC';
                $ratings->orderBy($arr_fields[$colIdx], $dir);
            } else {
                $ratings->orderBy('ratings.id', 'DESC');
            }
        } else {
            $ratings->orderBy('ratings.id', 'DESC');
        }

        // Set final limit and records
        if (!empty($limit)) {
            $ratings = $ratings->skip($offset)->take($limit);
            return $ratings->get();
        } else {
            return $ratings->get()->count();
        }
    }

    /**
     * Get Reviews Summary for KPI Card
     */
    public function scopeGetReviewsSummary($model, $search = null, $filter = array())
    {
        $query = Rating::leftJoin('users', function($join){
            $join->on('users.id', '=', 'ratings.user_id');
        });

        self::applyFiltersQuery($query, $search, $filter);

        $totalReviews = (clone $query)->count('ratings.id');
        $avgRatingVal = (clone $query)->whereNotNull('ratings.rating')->where('ratings.rating', '>', 0)->avg('ratings.rating');
        $averageRating = $avgRatingVal ? round((float)$avgRatingVal, 2) : 0.00;

        $fiveStarReviews = (clone $query)->where('ratings.rating', 5)->count();
        $fourStarReviews = (clone $query)->where('ratings.rating', 4)->count();
        $threeStarReviews = (clone $query)->where('ratings.rating', 3)->count();
        $twoStarReviews = (clone $query)->where('ratings.rating', 2)->count();
        $oneStarReviews = (clone $query)->where('ratings.rating', 1)->count();
        $unratedReviews = (clone $query)->where(function($q) {
            $q->whereNull('ratings.rating')->orWhere('ratings.rating', '<=', 0);
        })->count();

        $writtenMessages = (clone $query)->whereNotNull('ratings.message')->whereRaw("TRIM(ratings.message) != ''")->count();

        $fiveStarPercent = $totalReviews > 0 ? round(($fiveStarReviews / $totalReviews) * 100, 1) : 0;
        $fourStarPercent = $totalReviews > 0 ? round(($fourStarReviews / $totalReviews) * 100, 1) : 0;
        $threeStarPercent = $totalReviews > 0 ? round(($threeStarReviews / $totalReviews) * 100, 1) : 0;
        $twoStarPercent = $totalReviews > 0 ? round(($twoStarReviews / $totalReviews) * 100, 1) : 0;
        $oneStarPercent = $totalReviews > 0 ? round(($oneStarReviews / $totalReviews) * 100, 1) : 0;
        $unratedPercent = $totalReviews > 0 ? round(($unratedReviews / $totalReviews) * 100, 1) : 0;

        return [
            'total_reviews' => $totalReviews,
            'average_rating' => number_format($averageRating, 2),
            'five_star_reviews' => $fiveStarReviews,
            'four_star_reviews' => $fourStarReviews,
            'three_star_reviews' => $threeStarReviews,
            'two_star_reviews' => $twoStarReviews,
            'one_star_reviews' => $oneStarReviews,
            'unrated_reviews' => $unratedReviews,
            'written_messages' => $writtenMessages,
            'five_star_percent' => $fiveStarPercent,
            'four_star_percent' => $fourStarPercent,
            'three_star_percent' => $threeStarPercent,
            'two_star_percent' => $twoStarPercent,
            'one_star_percent' => $oneStarPercent,
            'unrated_percent' => $unratedPercent,
        ];
    }
}
