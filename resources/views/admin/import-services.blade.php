@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">Import Services</div>
                <div class="card-body">
                    <p>Upload a <strong>TXT</strong> file (each line: <code>name|provider|duration_minutes|available_slots|description</code>) or a <strong>PDF</strong> file. For PDFs the app will attempt to extract text if a PDF parser is installed; otherwise the file name will be used as the service title.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.services.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file" class="form-label">Select TXT or PDF file</label>
                            <input type="file" name="file" id="file" class="form-control" accept=".txt,.pdf" required>
                        </div>

                        <button class="btn btn-primary">Import</button>
                        <a href="{{ route('admin.services') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
