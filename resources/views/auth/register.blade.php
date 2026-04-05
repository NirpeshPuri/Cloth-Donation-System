<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ThreadsOfHope</title>
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
                <h2 class="text-2xl font-bold text-white">Join ThreadsOfHope</h2>
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
                    <input type="number" name="age" value="{{ old('age') }}" min="18" max="120"
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

</html>
