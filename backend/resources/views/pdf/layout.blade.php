<head>
    <style>
        table, th, td {
            border: 0.5px solid #333;
            border-collapse: collapse;
            font-family: 'Inter', sans-serif;
            padding: 0.375rem;
            font-size: 0.75rem;
            line-height: 1rem;
        }
        table {
            width: 100%;
        }
        th {
            background-color: #ccc;
        }
        @page {
          header: page-header;
          footer: page-footer;
        }
    </style>
</head>
<body>
        
<htmlpageheader name="page-header">
    Your Header Content
</htmlpageheader>

@yield('content')

<htmlpagefooter name="page-footer">
    Your Footer Content
</htmlpagefooter>


</body>
