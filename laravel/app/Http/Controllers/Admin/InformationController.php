<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InformationRequest;
use App\Http\Services\Information\InformationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class InformationController extends Controller
{
    /**
     * @var InformationService
     */
    protected $informationService;

    /**
     * Dependency Injection.
     *
     * @param InformationService $informationService
     */
    public function __construct(InformationService $informationService)
    {
        $this->informationService = $informationService;
    }

    /**
     * Information List.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.informations.index', [
            'informations' => $this->informationService->listAll(),
        ]);
    }

    /**
     * Information List.
     *
     * @return Response
     */
    public function frontendIndex()
    {
        App::setLocale(Session::get('lang'));
        return view('news', [
            'news' => $this->informationService->listAll(),
        ]);
    }

    /**
     * Create Information.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.informations.create');
    }

    /**
     * Store Information.
     *
     * @return Response
     */
    public function store(InformationRequest $request)
    {
        if (!$this->informationService->store($request->all())) {
            return back()->withInput();
        }
        return redirect('admin/informations');
    }

    /**
     * Edit Information.
     *
     * @param mixed $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return view('admin.informations.edit', [
            'information' => $this->informationService->findById($id),
        ]);
    }

    /**
     * Update Information.
     *
     * @param mixed $id
     *
     * @return Response
     */
    public function update(InformationRequest $request, $id)
    {
        $information = $this->informationService->findById($id);
        if (!$this->informationService->update($request->all(), $information)) {
            return back()->withInput();
        }
        return redirect('admin/informations');
    }

    /**
     * Delete Information.
     *
     * @param int $id
     *
     * @return obj
     */
    public function destroy($id)
    {
        $information = $this->informationService->findById($id);
        if (!$information->delete()) {
            return back()->withInput();
        }
        return redirect('admin/informations');
    }
}
