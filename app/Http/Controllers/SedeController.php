<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Queries\DefaultQuery;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class SedeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        return Inertia::render('Dashboard/Sedes');
    }

    public function loadItems()
    {
        $itemsPerPage = Request::get('itemsPerPage', 10);
        $sortBy = json_decode(Request::get('sortBy', '[]'), true);
        $search = json_decode(Request::get('search', '[]'), true);
        $deleted = filter_var(Request::get('deleted', 'false'), FILTER_VALIDATE_BOOLEAN);

        $consultas = new DefaultQuery();
        $query = Sede::query();

        $consultas->deleted($deleted, $query);
        $consultas->search($search, $query);
        $consultas->sort($sortBy, $query);
        $consultas->paginacion($itemsPerPage, $query);

        $items = $query->paginate($itemsPerPage);

        return [
            'tableData' => [
                'items' => $items->items(),
                'itemsLength' => $items->total(),
                'itemsPerPage' => $items->perPage(),
                'page' => $items->currentPage(),
                'sortBy' => $sortBy,
                'search' => $search,
                'deleted' => $deleted,
            ]
        ];
    }

    public function store()
    {
        Sede::create(
            Request::validate([
                'nombre' => ['required', 'max:191'],
            ])
        );

        return Redirect::back()->with('success', 'Sede creada.');
    }

    public function update(Sede $sede)
    {
        $sede->update(
            Request::validate([
                'nombre' => ['required', 'max:191'],
            ])
        );

        return Redirect::back()->with('success', 'Sede editada.');
    }

    public function destroy(Sede $sede)
    {
        $sede->delete();

        return Redirect::back()->with('success', 'Sede movida a la papelera.');
    }

    public function destroyPermanent($id)
    {
        $sede = Sede::onlyTrashed()->findOrFail($id);
        $sede->forceDelete();

        return Redirect::back()->with('success', 'Sede eliminada de forma permanente.');
    }

    public function restore($id)
    {
        $sede = Sede::onlyTrashed()->findOrFail($id);
        $sede->restore();

        return Redirect::back()->with('success', 'Sede restaurada.');
    }

    public function exportExcel()
    {
        $items = Sede::all();

        return  ['itemsExcel' => $items];
    }
}
