<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Report Card - {{ $student->student_name ?? 'Student' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f6f8fa;
            margin: 0;
            padding: 20px;
        }

        .report-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 10mm 20mm 20mm 20mm;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            min-height: 297mm;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .school-logo {
            width: 50px;
            height: 50px;
            margin-right: 12px;
        }

        .school-info h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 22px;
            font-weight: bold;
        }

        .school-info p {
            color: #7f8c8d;
            margin: 2px 0;
            font-size: 12px;
        }

        .report-title {
            color: #2c3e50;
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .academic-year {
            color: #7f8c8d;
            margin: 2px 0;
            font-size: 11px;
        }

        .section {
            background: #ecf0f1;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            border: 2px solid #bdc3c7;
        }

        .section-title {
            color: #2c3e50;
            margin: 0 0 12px 0;
            font-size: 15px;
            border-bottom: 2px solid;
            padding-bottom: 6px;
        }

        .section-title.blue { border-bottom-color: #3498db; }
        .section-title.orange { border-bottom-color: #e67e22; }
        .section-title.red { border-bottom-color: #e74c3c; }
        .section-title.purple { border-bottom-color: #9b59b6; }
        .section-title.yellow { border-bottom-color: #f39c12; }

        .section-icon {
            margin-right: 8px;
        }

        .section-icon.blue { color: #3498db; }
        .section-icon.orange { color: #e67e22; }
        .section-icon.red { color: #e74c3c; }
        .section-icon.purple { color: #9b59b6; }
        .section-icon.yellow { color: #f39c12; }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px 0;
            font-size: 11px;
        }

        .info-table td:first-child {
            font-weight: bold;
            color: #2c3e50;
            width: 40%;
        }

        .info-table td:last-child {
            color: #34495e;
            border-bottom: 1px solid #bdc3c7;
        }

        .performance-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            font-size: 10px;
        }

        .performance-table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border-right: 1px solid #2c3e50;
            background: #34495e;
            color: white;
        }

        .performance-table th:last-child {
            border-right: none;
        }

        .performance-table td {
            padding: 8px;
            border-right: 1px solid #dee2e6;
        }

        .performance-table td:last-child {
            border-right: none;
        }

        .performance-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 2px 5px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 9px;
        }

        .status-excellent {
            background: #28a745;
            color: white;
        }

        .status-good {
            background: #ffc107;
            color: #000;
        }

        .status-need-help {
            background: #dc3545;
            color: white;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            font-size: 10px;
        }

        .attendance-table th {
            padding: 6px;
            text-align: center;
            background: #34495e;
            color: white;
        }

        .attendance-table td {
            padding: 6px;
            text-align: center;
        }

        .attendance-present {
            background: #d4edda;
            color: #155724;
            font-weight: bold;
        }

        .attendance-absent {
            background: #f8d7da;
            color: #721c24;
            font-weight: bold;
        }

        .attendance-leave {
            background: #fff3cd;
            color: #856404;
            font-weight: bold;
        }

        .attendance-total {
            background: #f8f9fa;
            font-weight: bold;
        }

        .subject-attendance {
            background: white;
            padding: 8px;
            border-radius: 8px;
            margin-bottom: 6px;
            border-left: 4px solid #9b59b6;
        }

        .subject-attendance h5 {
            margin: 0 0 4px 0;
            color: #2c3e50;
            font-size: 11px;
        }

        .subject-attendance .stats {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }

        .fee-status-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 12px;
            border-radius: 8px;
        }

        .fee-status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 11px;
        }

        .fee-paid {
            background: #d4edda;
            color: #155724;
        }

        .fee-unpaid {
            background: #f8d7da;
            color: #721c24;
        }

        .footer {
            text-align: center;
            border-top: 3px solid #2c3e50;
            padding-top: 12px;
            color: #7f8c8d;
        }

        .footer p {
            margin: 0 0 6px 0;
            font-size: 11px;
        }

        .footer .small {
            font-size: 9px;
            font-style: italic;
        }

        .export-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .export-btn:hover {
            background: #34495e;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
        }

        .whatsapp-btn {
            position: fixed;
            top: 20px;
            right: 200px;
            background: #25d366;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .whatsapp-btn:hover {
            background: #128c7e;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
        }

        .whatsapp-btn:disabled {
            background: #95a5a6;
            cursor: not-allowed;
            transform: none;
        }

        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #7f8c8d;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #95a5a6;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .export-btn, .back-btn, .whatsapp-btn {
                display: none;
            }

            .report-container {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('admin.generate.report') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>

    <button class="export-btn" onclick="exportPDF()">
        <i class="fas fa-download"></i> Export PDF
    </button>

    <button class="whatsapp-btn" onclick="sendReportToParent()">
        <i class="fab fa-whatsapp"></i> Send to Parent
    </button>



    <div class="report-container" id="report-content">
        <!-- School Header -->
        <div class="header">
            <div class="header-content">
                <img src="/images/smislogo.png" alt="School Logo" class="school-logo">
                <div class="school-info">
                    <h1>PUSAT TUISYEN PERINTIS DIDIK</h1>
                    <p>Academic Excellence Through Dedication</p>
                </div>
            </div>
            <h2 class="report-title">STUDENT REPORT CARD</h2>
            <p class="academic-year">Academic Year 2024/2025</p>
        </div>

        <!-- Student Information Section -->
        <div class="section">
            <h3 class="section-title blue">
                <i class="fas fa-user-graduate section-icon blue"></i>
                STUDENT INFORMATION
            </h3>
            <div class="grid-2">
                <div>
                    <table class="info-table">
                        <tr>
                            <td>Student Name:</td>
                            <td>{{ $student->student_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>School:</td>
                            <td>{{ $student->school_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Class:</td>
                            <td>{{ $student->class_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td>{{ $student->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table class="info-table">
                        <tr>
                            <td>Average Score:</td>
                            <td><strong>{{ $summary['average_score'] ?? 0 }}%</strong></td>
                        </tr>
                        <tr>
                            <td>Total Subjects:</td>
                            <td><strong>{{ $summary['total_subjects'] ?? 0 }}</strong></td>
                        </tr>
                        <tr>
                            <td>Report Period:</td>
                            <td>{{ $month ?? 'N/A' }} 2024</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Parent Information Section -->
        <div class="section">
            <h3 class="section-title orange">
                <i class="fas fa-users section-icon orange"></i>
                PARENT/GUARDIAN INFORMATION
            </h3>
            <div class="grid-2">
                <div>
                    <table class="info-table">
                        <tr>
                            <td>Parent Name:</td>
                            <td>{{ $parent['name'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td>{{ $parent['phone'] ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table class="info-table">
                        <tr>
                            <td>Email:</td>
                            <td>{{ $parent['email'] ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Academic Performance Section -->
        <div class="section">
            <h3 class="section-title red">
                <i class="fas fa-chart-line section-icon red"></i>
                ACADEMIC PERFORMANCE
            </h3>
            <table class="performance-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th style="text-align: center;">Score (%)</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Teacher</th>
                        <th style="text-align: center;">Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr>
                        <td style="font-weight: bold; color: #2c3e50;">{{ $subject['subject_name'] }}</td>
                        <td style="text-align: center; font-weight: bold; font-size: 11px;">{{ round($subject['score']) }}%</td>
                        <td style="text-align: center;">
                            <span class="status-badge
                                @if($subject['performance_status'] == 'Excellent') status-excellent
                                @elseif($subject['performance_status'] == 'Good') status-good
                                @else status-need-help
                                @endif">
                                {{ $subject['performance_status'] }}
                            </span>
                        </td>
                        <td style="text-align: center; color: #7f8c8d; font-style: italic;">{{ $subject['teacher_name'] ?? 'N/A' }}</td>
                        <td style="text-align: left; color: #7f8c8d; font-style: italic; max-width: 120px; word-wrap: break-word;">{{ $subject['comments'] ?? 'No comments available.' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px; color: #7f8c8d;">No performance data available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Attendance Section -->
        <div class="section">
            <h3 class="section-title purple">
                <i class="fas fa-calendar-check section-icon purple"></i>
                ATTENDANCE RECORD
            </h3>
            <div class="grid-2">
                <div>
                    <h4 style="color: #2c3e50; margin-bottom: 8px; font-size: 13px;">Overall Attendance Summary</h4>
                    <table class="attendance-table">
                        <tr>
                            <th>Status</th>
                            <th>Days</th>
                            <th>Percentage</th>
                        </tr>
                        <tr>
                            <td class="attendance-present">Present</td>
                            <td>{{ $attendance_summary['present_days'] ?? 0 }}</td>
                            <td>{{ $attendance_summary['total_days'] > 0 ? round(($attendance_summary['present_days'] / $attendance_summary['total_days']) * 100) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="attendance-absent">Absent</td>
                            <td>{{ $attendance_summary['absent_days'] ?? 0 }}</td>
                            <td>{{ $attendance_summary['total_days'] > 0 ? round(($attendance_summary['absent_days'] / $attendance_summary['total_days']) * 100) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="attendance-leave">Leave</td>
                            <td>{{ $attendance_summary['leave_days'] ?? 0 }}</td>
                            <td>{{ $attendance_summary['total_days'] > 0 ? round(($attendance_summary['leave_days'] / $attendance_summary['total_days']) * 100) : 0 }}%</td>
                        </tr>
                        <tr class="attendance-total">
                            <td>Total</td>
                            <td>{{ $attendance_summary['total_days'] ?? 0 }}</td>
                            <td>100%</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <h4 style="color: #2c3e50; margin-bottom: 8px; font-size: 13px;">Attendance by Subject</h4>
                    @if(!empty($attendance_summary['by_subject']))
                        @foreach($attendance_summary['by_subject'] as $subject => $stats)
                        <div class="subject-attendance">
                            <h5>{{ $subject }}</h5>
                            <div class="stats">
                                <span>Present: {{ $stats['present'] }}/{{ $stats['total_days'] }}</span>
                                <span style="color: {{ $stats['percentage'] >= 80 ? '#28a745' : ($stats['percentage'] >= 60 ? '#ffc107' : '#dc3545') }}; font-weight: bold;">{{ $stats['percentage'] }}%</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p style="color: #7f8c8d; font-style: italic; font-size: 10px;">No attendance data available for this month.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Fee Status Section -->
        <div class="section">
            <h3 class="section-title yellow">
                <i class="fas fa-money-bill-wave section-icon yellow"></i>
                FEE STATUS
            </h3>
            <div class="fee-status-container">
                <div>
                    <h4 style="margin: 0 0 4px 0; color: #2c3e50; font-size: 13px;">Monthly Fee Status</h4>
                    <p style="margin: 0; color: #7f8c8d; font-size: 10px;">Period: {{ $month ?? 'N/A' }} 2024</p>
                </div>
                <div style="text-align: right;">
                    <div style="margin-bottom: 4px;">
                        <span class="fee-status-badge {{ $fee_status['status'] == 'Paid' ? 'fee-paid' : 'fee-unpaid' }}">
                            {{ $fee_status['status'] ?? 'Unknown' }}
                        </span>
                    </div>
                    <p style="margin: 0; color: #2c3e50; font-weight: bold; font-size: 14px;">RM {{ $fee_status['amount'] ?? '0.00' }}</p>
                </div>
            </div>
        </div>

        <!-- Report Footer -->
        <div class="footer">
            <p><strong>Report Generated:</strong> {{ date('l, F j, Y') }}</p>
            <p class="small">
                This is an official academic report from Pusat Tuisyen Perintis Didik.<br>
                For any inquiries, please contact the administration office.
            </p>
        </div>
    </div>

    <script>
        function exportPDF() {
            const element = document.getElementById('report-content');
            const opt = {
                margin: [10, 10, 10, 10], // [top, right, bottom, left] in mm
                filename: 'student_report_{{ $student->student_name ?? "student" }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    letterRendering: true,
                    allowTaint: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait',
                    compress: true
                },
                pagebreak: { mode: 'avoid-all' }
            };
            html2pdf().set(opt).from(element).save();
        }

                function sendReportToParent() {
            console.log('sendReportToParent function called');

            const button = document.querySelector('.whatsapp-btn');
            if (!button) {
                console.error('WhatsApp button not found');
                return;
            }

            const originalContent = button.innerHTML;
            console.log('Original button content:', originalContent);

            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            // Get current URL parameters
            const urlParts = window.location.pathname.split('/');
            const studentID = urlParts[urlParts.length - 3];
            const classID = urlParts[urlParts.length - 2];
            const month = decodeURIComponent(urlParts[urlParts.length - 1]);

            console.log('URL parts:', urlParts);
            console.log('Sending report for:', { studentID, classID, month });

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                button.innerHTML = originalContent;
                button.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'CSRF token not found'
                });
                return;
            }

            // Send AJAX request with timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout

            const requestUrl = `/admin/send-report-pdf/${studentID}/${classID}/${encodeURIComponent(month)}`;
            console.log('Request URL:', requestUrl);

            fetch(requestUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                signal: controller.signal
            })
            .then(response => {
                clearTimeout(timeoutId);
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    button.innerHTML = '<i class="fas fa-check"></i> Sent!';
                    button.style.background = '#43b324';

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Report Sent!',
                        text: `Report card sent successfully to ${data.parent_name}`,
                        timer: 3000,
                        showConfirmButton: false
                    });

                    // Reset button after 3 seconds
                    setTimeout(() => {
                        button.innerHTML = originalContent;
                        button.style.background = '#25d366';
                        button.disabled = false;
                    }, 3000);
                } else {
                    button.innerHTML = originalContent;
                    button.disabled = false;

                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Send',
                        text: data.message || 'Failed to send report to parent'
                    });
                }
            })
                        .catch(error => {
                clearTimeout(timeoutId);
                console.error('Error details:', error);
                console.error('Error name:', error.name);
                console.error('Error message:', error.message);

                button.innerHTML = originalContent;
                button.disabled = false;

                let errorMessage = 'Network error. Please try again.';
                if (error.name === 'AbortError') {
                    errorMessage = 'Request timed out. Please try again.';
                } else if (error.message) {
                    errorMessage = `Error: ${error.message}`;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            });
        }


    </script>
</body>
</html>
