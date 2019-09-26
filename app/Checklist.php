<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Checklist extends Model
{
   //
   protected $table = 'checklists';
   protected $fillable = ['object_domain','description','is_completed','due','urgency'];
}