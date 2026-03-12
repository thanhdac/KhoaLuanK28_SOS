<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - Hệ Thống Cứu Hộ</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background: white;
            padding: 40px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        header h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        header p {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        .api-version {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .search-box {
            margin-top: 20px;
        }

        .search-box input {
            width: 100%;
            max-width: 500px;
            padding: 12px 20px;
            border: 2px solid #667eea;
            border-radius: 25px;
            font-size: 1em;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.4);
        }

        .table-of-contents {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .table-of-contents h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .toc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .toc-item {
            padding: 15px;
            background: #f8f9ff;
            border-left: 4px solid #667eea;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .toc-item:hover {
            background: #667eea;
            color: white;
            transform: translateX(5px);
        }

        .toc-item a {
            text-decoration: none;
            color: inherit;
            font-weight: 500;
        }

        .api-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .section-header h2 {
            color: #667eea;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .section-header p {
            color: #666;
            font-size: 1.05em;
        }

        .endpoint-group {
            margin-bottom: 40px;
        }

        .endpoint-group h3 {
            color: #764ba2;
            font-size: 1.5em;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }

        .endpoint {
            background: #f8f9ff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .endpoint:hover {
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
            border-color: #667eea;
        }

        .endpoint-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .method {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.85em;
            color: white;
            min-width: 50px;
            text-align: center;
        }

        .method.get {
            background: #4CAF50;
        }

        .method.post {
            background: #2196F3;
        }

        .method.put {
            background: #FF9800;
        }

        .method.delete {
            background: #f44336;
        }

        .endpoint-path {
            font-family: 'Courier New', monospace;
            background: white;
            padding: 10px 15px;
            border-radius: 4px;
            flex-grow: 1;
            min-width: 300px;
            color: #667eea;
            font-weight: 500;
        }

        .endpoint-description {
            color: #555;
            margin-bottom: 15px;
            font-size: 1.05em;
        }

        .endpoint-description strong {
            color: #333;
        }

        .params-section {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border-left: 3px solid #667eea;
        }

        .params-section h4 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 0.95em;
        }

        .param {
            margin-bottom: 10px;
            padding: 8px;
            background: #f0f0f0;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .param-name {
            color: #667eea;
            font-weight: bold;
            font-family: 'Courier New', monospace;
        }

        .param-desc {
            color: #666;
            margin-top: 4px;
        }

        .required {
            color: #f44336;
            font-weight: bold;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .tab-button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        .tab-button:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }

        .example-code {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.85em;
            margin-top: 10px;
            line-height: 1.5;
        }

        .example-request {
            color: #4ec9b0;
        }

        .example-url {
            color: #9cdcfe;
        }

        .example-value {
            color: #ce9178;
        }

        .example-key {
            color: #9cdcfe;
        }

        footer {
            background: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            color: #666;
            margin-top: 40px;
        }

        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #667eea;
            color: white;
            padding: 12px 18px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            opacity: 0;
            transition: all 0.3s;
            cursor: pointer;
        }

        .back-to-top.show {
            opacity: 1;
        }

        .back-to-top:hover {
            background: #764ba2;
            transform: translateY(-5px);
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #667eea;
            background: white;
            color: #667eea;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: #667eea;
            color: white;
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 1.8em;
            }

            .api-section {
                padding: 20px;
            }

            .endpoint-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .endpoint-path {
                min-width: 100%;
                word-break: break-all;
            }

            .toc-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header>
            <h1>🚀 API Documentation</h1>
            <p>Hệ Thống Quản Lý & Điều Phối Cứu Hộ</p>
            <div class="api-version">Version 1.0</div>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="🔍 Tìm kiếm API...">
            </div>

            <div class="filter-buttons">
                <button class="filter-btn active" data-method="all">Tất Cả</button>
                <button class="filter-btn" data-method="GET">GET</button>
                <button class="filter-btn" data-method="POST">POST</button>
                <button class="filter-btn" data-method="PUT">PUT</button>
                <button class="filter-btn" data-method="DELETE">DELETE</button>
            </div>
        </header>

        <!-- Table of Contents -->
        <div class="table-of-contents">
            <h2>📑 Danh Mục API</h2>
            <div class="toc-grid">
                @foreach($docs as $index => $doc)
                    <div class="toc-item">
                        <a href="#section-{{ $index }}">{{ $doc['section'] }}</a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- API Sections -->
        @foreach($docs as $sectionIndex => $section)
            <div class="api-section" id="section-{{ $sectionIndex }}">
                <div class="section-header">
                    <h2>{{ $section['section'] }}</h2>
                    <p>{{ $section['description'] }}</p>
                </div>

                @foreach($section['endpoints'] as $endpoint)
                    <div class="endpoint-group">
                        <h3>📌 {{ $endpoint['name'] }}</h3>
                        <p style="color: #666; margin-bottom: 15px; font-size: 0.95em;">Base URL: <code style="background: #f0f0f0; padding: 2px 8px; border-radius: 3px;">{{ $endpoint['baseUrl'] }}</code></p>

                        @foreach($endpoint['methods'] as $method)
                            <div class="endpoint" data-method="{{ strtoupper($method['method']) }}">
                                <div class="endpoint-header">
                                    <span class="method {{ strtolower($method['method']) }}">{{ strtoupper($method['method']) }}</span>
                                    <div class="endpoint-path">{{ $endpoint['baseUrl'] }}{{ $method['path'] }}</div>
                                </div>
                                
                                <div class="endpoint-description">
                                    <strong>📝 Mô Tả:</strong> {{ $method['description'] }}
                                </div>

                                @if(count($method['params']) > 0)
                                    <div class="params-section">
                                        <h4>⚙️ Tham Số</h4>
                                        @foreach($method['params'] as $paramName => $paramDesc)
                                            <div class="param">
                                                <span class="param-name">{{ $paramName }}</span>
                                                <div class="param-desc">
                                                    @php
                                                        $isRequired = strpos($paramDesc, '(bắt buộc)') !== false;
                                                    @endphp
                                                    {{ $paramDesc }}
                                                    @if($isRequired)
                                                        <span class="required">*</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="params-section">
                                        <h4>⚙️ Tham Số</h4>
                                        <p style="color: #999;">Không có tham số</p>
                                    </div>
                                @endif

                                <div class="tabs">
                                    <button class="tab-button" onclick="showExample(this, 'request')">Ví Dụ Request</button>
                                    <button class="tab-button" onclick="showExample(this, 'response')">Ví Dụ Response</button>
                                </div>

                                <div class="example-code" style="display: none;">
                                    <div class="example-request"><span class="example-key">{{ strtoupper($method['method']) }}</span> <span class="example-url">{{ $endpoint['baseUrl'] }}{{ $method['path'] }}</span></div>
                                    <div style="margin-top: 10px; color: #6a9955;">
                                        @if(strtoupper($method['method']) === 'POST' || strtoupper($method['method']) === 'PUT')
                                            {<br/>
                                            @foreach($method['params'] as $paramName => $paramDesc)
                                                &nbsp;&nbsp;<span class="example-key">"{{ $paramName }}"</span>: <span class="example-value">"value"</span>,<br/>
                                            @endforeach
                                            }
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach

        <!-- Footer -->
        <footer>
            <p>© 2026 Hệ Thống Cứu Hộ. Tất cả các quyền được bảo lưu.</p>
            <p style="margin-top: 10px; font-size: 0.9em;">Base URL: <code style="background: #f0f0f0; padding: 2px 8px; border-radius: 3px;">{{ request()->getSchemeAndHttpHost() }}/api</code></p>
        </footer>

        <a href="#" class="back-to-top" onclick="scrollToTop()">↑ Top</a>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const endpoints = document.querySelectorAll('.endpoint');
            
            endpoints.forEach(endpoint => {
                const text = endpoint.textContent.toLowerCase();
                endpoint.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });

        // Filter by method
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const method = this.getAttribute('data-method');
                const endpoints = document.querySelectorAll('.endpoint');
                
                endpoints.forEach(endpoint => {
                    if (method === 'all') {
                        endpoint.style.display = 'block';
                    } else {
                        endpoint.style.display = endpoint.getAttribute('data-method') === method ? 'block' : 'none';
                    }
                });
            });
        });

        // Show example code
        function showExample(btn, type) {
            const parent = btn.parentElement.parentElement;
            const exampleCodes = parent.querySelectorAll('.example-code');
            exampleCodes.forEach(code => code.style.display = 'none');
            
            btn.parentElement.parentElement.querySelector('.example-code').style.display = 'block';
        }

        // Back to top button
        window.addEventListener('scroll', () => {
            const btn = document.querySelector('.back-to-top');
            if (window.scrollY > 300) {
                btn.classList.add('show');
            } else {
                btn.classList.remove('show');
            }
        });

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return false;
        }

        // Smooth scroll to sections
        document.querySelectorAll('.toc-item a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const target = document.querySelector(targetId);
                target.scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>
