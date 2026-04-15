{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Indian Inscriptions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Control Dashboard</h1>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Series</h5>
                    <h2>{{ $stats['total_series'] }}</h2>
                    <p class="mb-0">Active Collections</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Books</h5>
                    <h2>{{ $stats['total_books'] }}</h2>
                    <p class="mb-0">Published: {{ $stats['published_books'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Inscriptions</h5>
                    <h2>{{ $stats['total_inscriptions'] }}</h2>
                    <p class="mb-0">Total Records</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Citations</h5>
                    <h2>{{ $stats['total_citations'] }}</h2>
                    <p class="mb-0">Cross References</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Books by Series</h5>
                </div>
                <div class="card-body">
                    <canvas id="seriesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Citation Types</h5>
                </div>
                <div class="card-body">
                    <canvas id="citationChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Books</h5>
                    <button class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addBookModal">
                        <i class="fas fa-plus"></i> Add Book
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Series</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_books as $book)
                                <tr>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->series->code }}</td>
                                    <td>
                                        <span class="badge bg-{{ $book->status == 'published' ? 'success' : ($book->status == 'draft' ? 'warning' : 'secondary') }}">
                                            {{ $book->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="editBook({{ $book->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" onclick="publishBook({{ $book->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Inscriptions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Title</th>
                                    <th>Book</th>
                                    <th>Dynasty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_inscriptions as $inscription)
                                <tr>
                                    <td>{{ $inscription->inscription_number }}</td>
                                    <td>{{ Str::limit($inscription->title, 30) }}</td>
                                    <td>{{ $inscription->book->series->code }} Vol {{ $inscription->book->volume_number }}</td>
                                    <td>{{ $inscription->dynasty ?? 'Unknown' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Book Modal -->
<div class="modal fade" id="addBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addBookForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Series</label>
                        <select name="series_id" class="form-control" required>
                            <option value="">Select Series</option>
                            @foreach($series_distribution as $series)
                            <option value="{{ $series->id }}">{{ $series->code }} - {{ $series->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Volume Number</label>
                        <input type="text" name="volume_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
