@extends('layouts.master')

@section('title', 'Profile Settings')

@section('content')

    <div class="container mx-auto p-6 max-w-4xl">

        <h2 class="text-2xl font-bold mb-6">Profile Settings</h2>

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- ================= PROFILE UPDATE ================= --}}
        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="bg-white p-6 rounded shadow space-y-4">

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="font-semibold">Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label class="font-semibold">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled
                            class="w-full border p-2 rounded bg-gray-100">
                    </div>

                    <div>
                        <label class="font-semibold">Phone</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label class="font-semibold">Age</label>
                        <input type="number" name="age" value="{{ $user->age }}" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label class="font-semibold">Gender</label>
                        <select name="gender" class="w-full border p-2 rounded">
                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="font-semibold">Address</label>
                        <textarea name="address" class="w-full border p-2 rounded">{{ $user->address }}</textarea>
                    </div>

                    {{-- PROFILE PHOTO --}}
                    <div class="col-span-2">
                        <label class="font-semibold">Profile Photo</label>

                        <input type="file" id="profilePhotoInput" class="w-full border p-2 rounded">

                        <input type="hidden" name="profile_photo_base64" id="croppedImageData">

                        <div class="mt-3">
                            <img id="preview"
                                src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : '' }}"
                                class="w-24 h-24 rounded-full border cursor-pointer object-cover"
                                onclick="openImageModal(this.src)">
                        </div>
                    </div>

                    {{-- FULL IMAGE MODAL --}}
                    <div id="imageModal"
                        class="hidden fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50">

                        <img id="fullImage" class="max-w-3xl max-h-[90vh] rounded shadow-lg border-4 border-white">
                    </div>

                </div>

                <button class="bg-teal-600 text-white px-4 py-2 rounded">
                    Update Profile
                </button>

            </div>
        </form>

        {{-- ================= PASSWORD ================= --}}
        <form method="POST" action="{{ route('user.password.change') }}" class="mt-6">
            @csrf

            <div class="bg-white p-6 rounded shadow">

                <h3 class="font-bold mb-3">Change Password</h3>

                <input type="password" name="current_password" class="w-full border p-2 mb-2 rounded"
                    placeholder="Current Password">

                <input type="password" name="new_password" class="w-full border p-2 mb-2 rounded"
                    placeholder="New Password">

                <input type="password" name="new_password_confirmation" class="w-full border p-2 rounded"
                    placeholder="Confirm Password">

                <button class="mt-3 bg-red-500 text-white px-4 py-2 rounded">
                    Change Password
                </button>

            </div>
        </form>

        {{-- ================= LOCATION ================= --}}
        <div class="bg-white p-6 rounded shadow mt-6">

            <h3 class="font-bold mb-3">Location</h3>

            <div class="space-y-1 text-gray-700">
                <p><strong>Latitude:</strong> {{ $user->latitude ?? 'Not set' }}</p>
                <p><strong>Longitude:</strong> {{ $user->longitude ?? 'Not set' }}</p>
            </div>

            <button onclick="getLocation()" class="mt-3 bg-blue-500 text-white px-4 py-2 rounded">
                Use Current Location
            </button>

        </div>

    </div>

    {{-- ================= CROPPER ================= --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        let cropper;
        let modal;

        // OPEN CROPPER (BIG IMAGE)
        function openCropper(src) {

            modal = document.createElement('div');
            modal.innerHTML = `
    <div style="position:fixed;top:0;left:0;width:100%;height:100%;
        background:rgba(0,0,0,0.85);display:flex;justify-content:center;align-items:center;z-index:9999;">

        <div style="background:#fff;padding:20px;border-radius:10px;width:650px;">
            <h3 class="font-bold mb-2">Crop Image</h3>

            <div style="height:500px;">
                <img id="cropImg" style="max-width:100%;">
            </div>

            <div class="flex justify-between mt-3">
                <button id="cancel" class="px-3 py-2 bg-gray-400 text-white">Cancel</button>
                <button id="apply" class="px-3 py-2 bg-teal-600 text-white">Apply</button>
            </div>
        </div>
    </div>`;

            document.body.appendChild(modal);

            let img = modal.querySelector('#cropImg');
            img.src = src;

            cropper = new Cropper(img, {
                aspectRatio: 1,
                viewMode: 2,
                autoCropArea: 1
            });

            modal.querySelector('#cancel').onclick = () => {
                cropper.destroy();
                modal.remove();
            };

            modal.querySelector('#apply').onclick = () => {
                let canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400
                });
                let base64 = canvas.toDataURL();

                document.getElementById('preview').src = base64;
                document.getElementById('croppedImageData').value = base64;

                cropper.destroy();
                modal.remove();
            };
        }

        // FILE INPUT
        document.getElementById('profilePhotoInput').addEventListener('change', function(e) {
            let file = e.target.files[0];
            let reader = new FileReader();

            reader.onload = function(ev) {
                openCropper(ev.target.result);
            };

            reader.readAsDataURL(file);
        });

        // LOCATION
        function getLocation() {
            navigator.geolocation.getCurrentPosition(function(pos) {

                fetch("{{ route('user.location.update') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            latitude: pos.coords.latitude,
                            longitude: pos.coords.longitude
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert("Location updated!");
                        location.reload();
                    });

            });
        }

        // OPEN FULL IMAGE
        function openImageModal(src) {
            if (!src) return;

            document.getElementById('fullImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        // CLOSE ON CLICK
        document.getElementById('imageModal').addEventListener('click', function() {
            this.classList.add('hidden');
        });
    </script>

@endsection
