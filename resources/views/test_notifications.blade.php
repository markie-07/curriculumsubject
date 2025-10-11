@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Test Notification System</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <button onclick="testNotification('success', 'Success!', 'This is a success notification')" 
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                Test Success
            </button>
            
            <button onclick="testNotification('error', 'Error!', 'This is an error notification')" 
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                Test Error
            </button>
            
            <button onclick="testNotification('warning', 'Warning!', 'This is a warning notification')" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors">
                Test Warning
            </button>
            
            <button onclick="testNotification('info', 'Info!', 'This is an info notification')" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                Test Info
            </button>
        </div>
        
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Test AJAX Response</h2>
            <button onclick="testAjaxNotification()" 
                    class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors">
                Test AJAX Response with Notification
            </button>
        </div>
    </div>
</div>

<script>
function testNotification(type, title, message) {
    if (typeof notificationManager !== 'undefined') {
        notificationManager.show(type, title, message);
    } else {
        alert('Notification manager not found!');
    }
}

function testAjaxNotification() {
    // Simulate an AJAX response with notification data
    const mockResponse = {
        message: 'Operation completed successfully!',
        notification: {
            type: 'success',
            title: 'AJAX Test!',
            message: 'This notification came from a simulated AJAX response'
        }
    };
    
    if (typeof handleAjaxResponse !== 'undefined') {
        handleAjaxResponse(mockResponse);
    } else {
        alert('handleAjaxResponse function not found!');
    }
}
</script>
@endsection
