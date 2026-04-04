<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * Test registration stores data in database
     */
    public function test_registration_stores_in_database()
    {
        $uniqueEmail = 'test_'.date('Ymd_His').'@example.com';
        $uniquePhone = '98'.substr(time(), -8);

        $registerData = [
            'name' => 'Database Test User',
            'email' => $uniqueEmail,
            'phone' => $uniquePhone,
            'age' => 25,
            'gender' => 'male',
            'address' => '123 Test Street',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $registerData);
        echo "\nResponse status: ".$response->status()."\n";

        if ($response->status() != 302) {
            echo 'Response content: '.$response->getContent()."\n";
        }

        $response->assertRedirect('/login');

        $user = User::where('email', $uniqueEmail)->first();

        if ($user) {
            echo "\n✅ SUCCESS! User stored in database:\n";
            echo '   ID: '.$user->id."\n";
            echo '   Name: '.$user->name."\n";
            echo '   Email: '.$user->email."\n";
            echo '   Phone: '.$user->phone."\n";
            echo '   Address: '.$user->address."\n";
            echo '   Age: '.$user->age."\n";
            echo '   Gender: '.$user->gender."\n";
            echo '   Password is hashed: '.(Hash::check('password123', $user->password) ? 'Yes' : 'No')."\n";

            $this->assertTrue(true);
        } else {
            echo "\n❌ FAILED! User not found in database\n";
            $this->assertTrue(false);
        }

        echo "\n📝 User KEPT in database\n";
    }

    /**
     * Test different phones are allowed
     */
    public function test_different_phones_are_allowed()
    {
        $uniqueId = uniqid();
        $commonPassword = 'password123';

        // First user with phone 1
        $phone1 = '981'.substr(str_replace('.', '', microtime(true)), -7);
        $email1 = 'same_'.$uniqueId.'_1@example.com';

        // Second user with phone 2 (different)
        $phone2 = '982'.substr(str_replace('.', '', microtime(true)), -7);
        $email2 = 'same_'.$uniqueId.'_2@example.com';

        $registerData1 = [
            'name' => 'User One',
            'email' => $email1,
            'phone' => $phone1,
            'address' => 'Address 1',
            'password' => $commonPassword,
            'password_confirmation' => $commonPassword,
        ];

        $registerData2 = [
            'name' => 'User Two',
            'email' => $email2,
            'phone' => $phone2,
            'address' => 'Address 2',
            'password' => $commonPassword,
            'password_confirmation' => $commonPassword,
        ];

        // Register first user
        $response1 = $this->post('/register', $registerData1);

        if (is_array($response1)) {
            echo "\n❌ First registration failed\n";
            $this->assertTrue(false);

            return;
        }

        if ($response1->status() != 302) {
            echo 'First registration failed: '.$response1->getContent()."\n";
            $this->assertTrue(false);

            return;
        }

        // Register second user
        $response2 = $this->post('/register', $registerData2);

        if (is_array($response2)) {
            echo "\n❌ Second registration failed\n";
            $this->assertTrue(false);

            return;
        }

        if ($response2->status() != 302) {
            echo 'Second registration failed: '.$response2->getContent()."\n";
            $this->assertTrue(false);

            return;
        }

        $response1->assertRedirect('/login');
        $response2->assertRedirect('/login');

        // Verify both users exist
        $user1 = User::where('email', $email1)->first();
        $user2 = User::where('email', $email2)->first();

        if ($user1 && $user2) {
            echo "\n✅ Different phones allowed!\n";
            echo "   User 1: $email1 (Phone: $phone1)\n";
            echo "   User 2: $email2 (Phone: $phone2)\n";
            echo "   Both users created successfully\n";
            $this->assertTrue(true);
        } else {
            echo "\n❌ Different phones test failed!\n";
            $this->assertTrue(false);
        }

        echo "\n📝 Both users KEPT in database\n";
    }
}
