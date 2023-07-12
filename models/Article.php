<!-- Define Schema for Article in Article Page -->
<?php
class Article
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'article',
        'date',
        'comment',
        'reply',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ownership' => 'owned',
    ];
}
?>
