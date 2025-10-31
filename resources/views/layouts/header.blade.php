<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Software Management Dashboard - Clikzop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <style>
        .global-loader 
        {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s ease;
        }
        
        .loader-content 
        {
            text-align: center;
            max-width: 400px;
            padding: 20px;
        }
        
        .loader-spinner 
        {
            width: 60px;
            height: 60px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3762b8;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        
        .loader-text 
        {
            font-size: 16px;
            color: #3762b8;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .loader-subtext 
        {
            font-size: 14px;
            color: #6c757d;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn-loader 
        {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }
        .table-loader 
        {
            text-align: center;
            padding: 40px;
        }
        .table-loader-spinner 
        {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3762b8;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }
        .modal-loader 
        {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1050;
            border-radius: 0.3rem;
        }
        .progress-loader 
        {
            height: 4px;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10000;
            background: linear-gradient(90deg, #3762b8, #5a82eb, #3762b8);
            background-size: 200% 100%;
            animation: progressAnimation 1.5s infinite linear;
        }
        
        @keyframes progressAnimation {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .skeleton-loader 
        {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeletonLoading 1.5s infinite;
            border-radius: 4px;
        }
        
        @keyframes skeletonLoading 
        {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .skeleton-row 
        {
            height: 20px;
            margin-bottom: 10px;
        }
        .content-loading 
        {
            opacity: 0.6;
            pointer-events: none;
        }
        .select2-container--default .select2-selection--single 
        {
            height: 38px;
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered 
        {
            line-height: 40px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow 
        {
            height: 36px;
        }
        .select2-container .select2-selection--single 
        {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 37px !important;
            user-select: none;
            -webkit-user-select: none;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered 
        {
            color: #444;
            line-height: 36px !important;
        }
    </style>