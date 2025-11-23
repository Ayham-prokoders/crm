<?php

namespace Modules\Lms\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Lms\Models\ExternalCertificate;
use Modules\Lms\Http\Requests\ExternalCertificateRequest;
use Modules\Lms\Transformers\ExternalCertificateResource;

class ExternalCertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = QueryBuilder::for(ExternalCertificate::class)
            ->allowedFilters(['course', 'type', 'category', 'city', 'class', 'first_name', 'last_name'])
            ->allowedSorts(['created_at', 'course'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', '%' . $request->input('first_name') . '%');
        }

        $certificates = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'certificates' => ExternalCertificateResource::collection($certificates),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $certificates->total(),
                'per_page' => $certificates->perPage(),
                'current_page' => $certificates->currentPage(),
                'last_page' => $certificates->lastPage(),
                'from' => $certificates->firstItem(),
                'to' => $certificates->lastItem(),
            ];
        }

        return ResponseHelper::success($data);
    }

    public function store(ExternalCertificateRequest $request)
    {
        $data = $request->validated();

        if (empty($data['ID_certificate'])) {
            $data['ID_certificate'] = ExternalCertificate::generateUniqueCertificateID($data['first_name']);
        }

        $certificate = ExternalCertificate::create($data);
        return ResponseHelper::create(new ExternalCertificateResource($certificate));
    }

    public function show(ExternalCertificate $externalCertificate)
    {
        $externalCertificate->load('category');
        return ResponseHelper::success(new ExternalCertificateResource($externalCertificate));
    }


    public function update(ExternalCertificateRequest $request, ExternalCertificate $externalCertificate)
    {
        $data = $request->validated();
        $externalCertificate->update($data);
        return ResponseHelper::success(new ExternalCertificateResource($externalCertificate));
    }

    public function destroy(ExternalCertificate $externalCertificate)
    {
        $externalCertificate->delete();
        return ResponseHelper::success('External certificate deleted successfully.');
    }

    public function generateCertificateID()
    {
        do {
            $certificateID = strtoupper(Str::random(12));
        } while (ExternalCertificate::where('ID_certificate', $certificateID)->exists());

        return ResponseHelper::success([
            'status' => 'success',
            'certificateID' => $certificateID,
        ]);
    }

    public function getCertificate()
    {
        $data = ExternalCertificate::where('show_in_website', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return ResponseHelper::success(ExternalCertificateResource::collection($data));
    }

    public function showInWebsite(Request $request)
    {
        $certificateIds = $request->input('certificates', []);

        ExternalCertificate::whereIn('id', $certificateIds)
            ->update(['show_in_website' => true]);

        return ResponseHelper::success('Certificates updated successfully');
    }

}
