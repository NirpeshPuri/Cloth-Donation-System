{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cloth Bank</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0f2b2d 0%, #1e4a4b 100%);
        }

        /* Cropper modal styles */
        .cropper-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }

        .cropper-container-box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            max-width: 90%;
            width: 600px;
        }

        .cropper-image-container {
            max-height: 400px;
            overflow: hidden;
        }

        .cropper-buttons {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <!-- Back to Home Button -->
        <a href="{{ route('home') }}"
            class="absolute top-6 left-6 bg-white hover:bg-gray-100 text-teal-700 font-semibold px-4 py-2 rounded-full shadow-md transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>

        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="gradient-bg px-6 py-8 text-center">
                <i class="fas fa-hands-helping text-white text-4xl mb-3"></i>
                <h2 class="text-2xl font-bold text-white">Join Cloth Bank</h2>
                <p class="text-teal-100 mt-2">Create your account to donate & receive clothes</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('register.submit') }}" class="px-6 py-8" enctype="multipart/form-data"
                id="registerForm">
                @csrf

                <!-- Full Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Phone Number *</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Profile Photo with Cropper -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Profile Photo (Optional)</label>
                    <div class="mt-1">
                        <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    </div>

                    <!-- Hidden input to store cropped image as base64 -->
                    <input type="hidden" name="profile_photo_base64" id="croppedImageData">

                    <!-- Image Preview -->
                    <div id="currentPhotoPreview" class="mt-3 hidden">
                        <div class="flex items-center gap-3">
                            <img id="preview" src="#" alt="Preview"
                                class="w-16 h-16 rounded-full object-cover border-2 border-teal-500">
                            <button type="button" id="editPhotoBtn" class="text-teal-600 text-sm hover:underline">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" id="removePhotoBtn" class="text-red-600 text-sm hover:underline">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Allowed: JPG, JPEG, PNG, GIF. Max size: 2MB. You can
                        crop/resize after selecting.</p>
                    @error('profile_photo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('profile_photo_base64')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Age -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Age (Optional)</label>
                    <input type="number" name="age" value="{{ old('age') }}" min="16" max="120"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('age')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Gender (Optional)</label>
                    <select name="gender"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Address *</label>
                    <textarea name="address" rows="2" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="mb-6 bg-teal-50 rounded-lg p-4 border border-teal-200">
                    <div class="flex items-center gap-2 text-teal-700">
                        <i class="fas fa-info-circle"></i>
                        <span class="font-semibold text-sm">Flexible Account</span>
                    </div>
                    <p class="text-xs text-teal-600 mt-1">
                        As a member, you can both <strong>donate clothes</strong> you no longer need AND <strong>request
                            clothes</strong> when you need them. No restrictions!
                    </p>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Password *</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg transition duration-200 transform hover:scale-[1.02]">
                    Create Account <i class="fas fa-arrow-right ml-2"></i>
                </button>

                <!-- Login Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-teal-600 font-semibold hover:underline">Sign
                            In</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div id="cropperModal" class="cropper-modal-overlay">
        <div class="cropper-container-box">
            <h3 class="text-lg font-semibold mb-3">Crop & Adjust Image</h3>
            <div class="cropper-image-container">
                <img id="cropperImage" src="#" alt="Crop Image">
            </div>
            <div class="cropper-buttons">
                <button type="button" id="cancelCropBtn"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Cancel</button>
                <button type="button" id="applyCropBtn"
                    class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Apply Crop</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper = null;
        let currentFile = null;
        const modal = document.getElementById('cropperModal');
        const cropperImage = document.getElementById('cropperImage');
        const fileInput = document.getElementById('profilePhotoInput');
        const previewDiv = document.getElementById('currentPhotoPreview');
        const previewImg = document.getElementById('preview');
        const croppedImageData = document.getElementById('croppedImageData');

        // Handle file selection
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Please select an image file (JPG, PNG, GIF)');
                fileInput.value = '';
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                fileInput.value = '';
                return;
            }

            currentFile = file;
            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;
                modal.style.display = 'flex';

                // Initialize cropper
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 2,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            }
            reader.readAsDataURL(file);
        });

        // Apply crop
        document.getElementById('applyCropBtn').addEventListener('click', function() {
            if (cropper) {
                // Get cropped canvas
                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300,
                });

                // Convert canvas to blob
                canvas.toBlob(function(blob) {
                    // Convert to base64 for preview and storage
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewDiv.classList.remove('hidden');

                        // Store base64 in hidden field
                        croppedImageData.value = e.target.result;

                        // Clear the file input so it doesn't get submitted
                        fileInput.value = '';
                    };
                    reader.readAsDataURL(blob);

                    modal.style.display = 'none';
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                }, currentFile.type);
            }
        });

        // Cancel crop
        document.getElementById('cancelCropBtn').addEventListener('click', function() {
            modal.style.display = 'none';
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            fileInput.value = '';
        });

        // Edit photo
        document.getElementById('editPhotoBtn').addEventListener('click', function() {
            if (croppedImageData.value) {
                cropperImage.src = croppedImageData.value;
                modal.style.display = 'flex';
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 2,
                    dragMode: 'move',
                    autoCropArea: 1,
                });
                currentFile = new File([croppedImageData.value], 'cropped.jpg');
            }
        });

        // Remove photo
        document.getElementById('removePhotoBtn').addEventListener('click', function() {
            previewDiv.classList.add('hidden');
            croppedImageData.value = '';
            previewImg.src = '#';
            fileInput.value = '';
        });
    </script>
</body>

</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cloth Bank</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0f2b2d 0%, #1e4a4b 100%);
        }

        /* Cropper modal styles */
        .cropper-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }

        .cropper-container-box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            max-width: 90%;
            width: 600px;
        }

        .cropper-image-container {
            max-height: 400px;
            overflow: hidden;
        }

        .cropper-buttons {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        /* Error message styling */
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .error-message i {
            font-size: 0.75rem;
        }

        .input-error {
            border-color: #dc2626 !important;
        }

        .input-error:focus {
            ring-color: #dc2626 !important;
        }

        .field-error {
            border-left: 3px solid #dc2626;
            padding-left: 0.5rem;
        }

        /* Toast notification */
        .toast-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            color: white;
            font-weight: 500;
            z-index: 99999;
            animation: slideInRight 0.3s ease-out;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            max-width: 400px;
        }

        .toast-error {
            background: #dc2626;
        }

        .toast-success {
            background: #16a34a;
        }

        .toast-info {
            background: #3b82f6;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .toast-message .toast-close {
            margin-left: 1rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .toast-message .toast-close:hover {
            opacity: 1;
        }

        /* Loading spinner */
        .btn-loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-loading .btn-text {
            display: none;
        }

        .btn-loading .btn-spinner {
            display: inline-block;
        }

        .btn-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Password strength indicator */
        .password-strength {
            height: 4px;
            border-radius: 4px;
            margin-top: 6px;
            transition: all 0.3s ease;
            background: #e5e7eb;
        }

        .password-strength.weak {
            width: 25%;
            background: #dc2626;
        }

        .password-strength.medium {
            width: 50%;
            background: #f59e0b;
        }

        .password-strength.strong {
            width: 75%;
            background: #3b82f6;
        }

        .password-strength.very-strong {
            width: 100%;
            background: #16a34a;
        }

        .password-requirements {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 4px;
        }

        .password-requirements .valid {
            color: #16a34a;
        }

        .password-requirements .invalid {
            color: #dc2626;
        }

        /* Required field indicator */
        .required-star {
            color: #dc2626;
            margin-left: 2px;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <!-- Back to Home Button -->
        <a href="{{ route('home') }}"
            class="absolute top-6 left-6 bg-white hover:bg-gray-100 text-teal-700 font-semibold px-4 py-2 rounded-full shadow-md transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>

        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="gradient-bg px-6 py-8 text-center">
                <i class="fas fa-hands-helping text-white text-4xl mb-3"></i>
                <h2 class="text-2xl font-bold text-white">Join Cloth Bank</h2>
                <p class="text-teal-100 mt-2">Create your account to donate & receive clothes</p>
            </div>

            <!-- Display Validation Errors at Top -->
            @if ($errors->any())
                <div class="mx-6 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-red-700">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm text-red-600 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="mx-6 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register.submit') }}" class="px-6 py-8" enctype="multipart/form-data"
                id="registerForm">
                @csrf

                <!-- Full Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Full Name <span
                            class="required-star">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Enter your full name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('name') input-error @enderror">
                    @error('name')
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">Only letters and spaces allowed</p>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Email Address <span
                            class="required-star">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="your@email.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('email') input-error @enderror">
                    @error('email')
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Phone Number <span
                            class="required-star">*</span></label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder=""
                        maxlength="10"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('phone') input-error @enderror">
                    @error('phone')
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">Must start with 98 or 97 and be exactly 10 digits</p>
                </div>

                <!-- Profile Photo with Cropper -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Profile Photo (Optional)</label>
                    <div class="mt-1">
                        <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    </div>

                    <!-- Hidden input to store cropped image as base64 -->
                    <input type="hidden" name="profile_photo_base64" id="croppedImageData"
                        value="{{ old('profile_photo_base64') }}">

                    <!-- Image Preview -->
                    <div id="currentPhotoPreview" class="mt-3 {{ old('profile_photo_base64') ? '' : 'hidden' }}">
                        <div class="flex items-center gap-3">
                            <img id="preview" src="{{ old('profile_photo_base64') ?? '#' }}" alt="Preview"
                                class="w-16 h-16 rounded-full object-cover border-2 border-teal-500">
                            <button type="button" id="editPhotoBtn" class="text-teal-600 text-sm hover:underline">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" id="removePhotoBtn" class="text-red-600 text-sm hover:underline">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Allowed: JPG, JPEG, PNG. Max size: 2MB</p>
                    @error('profile_photo')
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                    @error('profile_photo_base64')
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Age -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Age <span
                            class="required-star">*</span></label>
                    <input type="number" name="age" value="{{ old('age') }}" required min="16"
                        max="120" placeholder="16-120"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('age') input-error @enderror">
                    @error('age')
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">Minimum age: 16 years</p>
                </div>

                <!-- Gender -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Gender <span
                            class="required-star">*</span></label>
                    <select name="gender" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('gender') input-error @enderror">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Address <span
                            class="required-star">*</span></label>
                    <textarea name="address" rows="2" required placeholder="Enter your full address"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('address') input-error @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="mb-6 bg-teal-50 rounded-lg p-4 border border-teal-200">
                    <div class="flex items-center gap-2 text-teal-700">
                        <i class="fas fa-info-circle"></i>
                        <span class="font-semibold text-sm">Flexible Account</span>
                    </div>
                    <p class="text-xs text-teal-600 mt-1">
                        As a member, you can both <strong>donate clothes</strong> you no longer need AND <strong>request
                            clothes</strong> when you need them. No restrictions!
                    </p>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Password <span
                            class="required-star">*</span></label>
                    <input type="password" name="password" id="password" required
                        placeholder="Minimum 6 characters"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('password') input-error @enderror">
                    <div class="password-strength" id="passwordStrength"></div>
                    <div class="password-requirements mt-1" id="passwordRequirements">
                        <span id="req-length">❌ At least 6 characters</span><br>
                        <span id="req-upper">❌ At least one uppercase letter</span><br>
                        <span id="req-lower">❌ At least one lowercase letter</span><br>
                        <span id="req-number">❌ At least one number</span><br>
                        <span id="req-special">❌ At least one special character (!@#$%^&*)</span>
                    </div>
                    @error('password')
                        <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Confirm Password <span
                            class="required-star">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="Confirm your password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg transition duration-200 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <span class="btn-spinner"></span>
                    <span class="btn-text">Create Account <i class="fas fa-arrow-right ml-2"></i></span>
                </button>

                <!-- Login Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-teal-600 font-semibold hover:underline">Sign
                            In</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div id="cropperModal" class="cropper-modal-overlay">
        <div class="cropper-container-box">
            <h3 class="text-lg font-semibold mb-3">Crop & Adjust Image</h3>
            <div class="cropper-image-container">
                <img id="cropperImage" src="#" alt="Crop Image">
            </div>
            <div class="cropper-buttons">
                <button type="button" id="cancelCropBtn"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Cancel</button>
                <button type="button" id="applyCropBtn"
                    class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Apply Crop</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        // ========== CROPPER FUNCTIONALITY ==========
        let cropper = null;
        let currentFile = null;
        const modal = document.getElementById('cropperModal');
        const cropperImage = document.getElementById('cropperImage');
        const fileInput = document.getElementById('profilePhotoInput');
        const previewDiv = document.getElementById('currentPhotoPreview');
        const previewImg = document.getElementById('preview');
        const croppedImageData = document.getElementById('croppedImageData');

        // If there's an existing cropped image on page load (from old input)
        if (croppedImageData.value) {
            previewDiv.classList.remove('hidden');
            previewImg.src = croppedImageData.value;
        }

        // Handle file selection
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file type
            if (!file.type.match('image.*')) {
                showToast('Please select an image file (JPG, PNG)', 'error');
                fileInput.value = '';
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showToast('File size must be less than 2MB', 'error');
                fileInput.value = '';
                return;
            }

            currentFile = file;
            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;
                modal.style.display = 'flex';

                // Initialize cropper
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 2,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            }
            reader.readAsDataURL(file);
        });

        // Apply crop
        document.getElementById('applyCropBtn').addEventListener('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300,
                });

                canvas.toBlob(function(blob) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const base64Data = e.target.result;
                        previewImg.src = base64Data;
                        previewDiv.classList.remove('hidden');
                        croppedImageData.value = base64Data;
                        fileInput.value = '';
                    };
                    reader.readAsDataURL(blob);

                    modal.style.display = 'none';
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    showToast('Image cropped successfully!', 'success');
                }, currentFile.type);
            }
        });

        // Cancel crop
        document.getElementById('cancelCropBtn').addEventListener('click', function() {
            modal.style.display = 'none';
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            fileInput.value = '';
        });

        // Edit photo
        document.getElementById('editPhotoBtn').addEventListener('click', function() {
            if (croppedImageData.value) {
                cropperImage.src = croppedImageData.value;
                modal.style.display = 'flex';
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 2,
                    dragMode: 'move',
                    autoCropArea: 1,
                });
                currentFile = new File([croppedImageData.value], 'cropped.jpg');
            }
        });

        // Remove photo
        document.getElementById('removePhotoBtn').addEventListener('click', function() {
            previewDiv.classList.add('hidden');
            croppedImageData.value = '';
            previewImg.src = '#';
            fileInput.value = '';
            showToast('Photo removed', 'info');
        });

        // ========== TOAST NOTIFICATION ==========
        function showToast(message, type = 'info') {
            const existingToast = document.querySelector('.toast-message');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.className = `toast-message toast-${type}`;

            let icon = 'fa-info-circle';
            if (type === 'success') icon = 'fa-check-circle';
            else if (type === 'error') icon = 'fa-exclamation-circle';

            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas ${icon} text-xl"></i>
                        <span>${message}</span>
                    </div>
                    <span class="toast-close" onclick="this.parentElement.parentElement.remove()">&times;</span>
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);
        }

        // ========== PASSWORD VALIDATION ==========
        const passwordInput = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');

        function validatePassword(password) {
            const requirements = {
                length: password.length >= 6,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password),
            };

            // Update requirement indicators
            document.getElementById('req-length').innerHTML =
                (requirements.length ? '✅' : '❌') + ' At least 6 characters';
            document.getElementById('req-upper').innerHTML =
                (requirements.upper ? '✅' : '❌') + ' At least one uppercase letter';
            document.getElementById('req-lower').innerHTML =
                (requirements.lower ? '✅' : '❌') + ' At least one lowercase letter';
            document.getElementById('req-number').innerHTML =
                (requirements.number ? '✅' : '❌') + ' At least one number';
            document.getElementById('req-special').innerHTML =
                (requirements.special ? '✅' : '❌') + ' At least one special character (!@#$%^&*)';

            // Update strength bar
            const strengthBar = document.getElementById('passwordStrength');
            const passedCount = Object.values(requirements).filter(Boolean).length;

            strengthBar.className = 'password-strength';
            if (password.length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.style.background = '#e5e7eb';
            } else if (passedCount <= 2) {
                strengthBar.classList.add('weak');
            } else if (passedCount <= 3) {
                strengthBar.classList.add('medium');
            } else if (passedCount <= 4) {
                strengthBar.classList.add('strong');
            } else {
                strengthBar.classList.add('very-strong');
            }

            return requirements;
        }

        passwordInput.addEventListener('input', function() {
            validatePassword(this.value);
        });

        // ========== PHONE NUMBER FORMATTING ==========
        document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/\D/g, '');
            // Limit to 10 digits
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
            // Validate prefix
            if (this.value.length >= 2) {
                const prefix = this.value.slice(0, 2);
                if (prefix !== '98' && prefix !== '97') {
                    this.setCustomValidity('Phone number must start with 98 or 97');
                } else {
                    this.setCustomValidity('');
                }
            }
        });

        // ========== NAME VALIDATION ==========
        document.querySelector('input[name="name"]').addEventListener('input', function(e) {
            // Only allow letters and spaces
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        });

        // ========== AGE VALIDATION ==========
        document.querySelector('input[name="age"]').addEventListener('input', function(e) {
            if (this.value < 16 && this.value !== '') {
                this.setCustomValidity('You must be at least 16 years old');
            } else if (this.value > 120) {
                this.setCustomValidity('Age must be less than 120');
            } else {
                this.setCustomValidity('');
            }
        });

        // ========== FORM SUBMISSION ==========
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');

            // Validate password match
            if (passwordInput.value !== passwordConfirm.value) {
                e.preventDefault();
                showToast('Passwords do not match!', 'error');
                return false;
            }

            // Validate password strength
            const requirements = validatePassword(passwordInput.value);
            if (!Object.values(requirements).every(Boolean)) {
                e.preventDefault();
                showToast('Please meet all password requirements!', 'error');
                return false;
            }

            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;
        });

        // ========== AUTO-HIDE ERRORS ==========
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('input', function() {
                const errorElement = this.closest('.mb-4')?.querySelector('.error-message');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
                this.classList.remove('input-error');
            });

            element.addEventListener('change', function() {
                const errorElement = this.closest('.mb-4')?.querySelector('.error-message');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
                this.classList.remove('input-error');
            });
        });
    </script>
</body>

</html>
