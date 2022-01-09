<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(Request $request) {
        $breed = $request->get('breed') === 'default' ? null : $request->get('breed');
        $name = $request->get('name');
        $type = $request->get('type') === 'default' ? null : $request->get('type');

        $animals = DB::table('animals')->get();
        $breeds = DB::table('breeds')->get();

        

        if ($breed || $name || $type) {
            $query = 'SELECT * FROM animals as a';
            $delimiter = false;

            if ($breed) {
                $query .= sprintf(" JOIN breeds as b on a.breed = b.id WHERE b.name = '%s'", $breed);
                $delimiter = true;
            }

            if ($name) {
                $operator = $delimiter ? 'AND' : 'WHERE';
                $queryName = '%' . $name . '%';
                $query .= sprintf(" %s name like '%s'", $operator, $queryName);
            }

            if ($type) {
                $operator = $delimiter ? 'AND' : 'WHERE';
                $queryType = '%' . $type . '%';
                $query .= sprintf(" %s type like '%s'", $operator, $queryType);
            }

            $animals = DB::select($query);
        }

        $types = [];
        foreach (DB::table('animals')->get() as $animal) {
            $types[] = $animal->type;
        }

        return view('index', [
            'animals' => $animals,
            'breeds' => $breeds,
            'selected_breed' => $breed,
            'name' => $name,
            'types' => array_unique($types),
            'selected_type' => $type
        ]);
    }
}
