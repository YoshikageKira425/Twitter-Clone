<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('is_admin')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string("profile_image")->default("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKkAAACUCAMAAADf7/luAAAAYFBMVEXZ3OFwd3/c3+Ryd3xydn9weHtweH1scXXh5OnZ3eBtdHxrdXfQ09hvdHjV2N1sdHrHytC+wsZ/hom2ur+Wm5+HjJBmbXSPkpewtbmfpKh7gIWprrJlbnDJztGNlZmFi5S+luYFAAAE4klEQVR4nO2c2ZajIBBAtVBxAfcVNf3/fzmYTKY73VlAMGXP8Z4z89QP94AURVHEcQ4ODg4ODg4ODg4ODg4ODg5eAAuh/Ict8hwInbwah2EYq9wJdysLkFaN68ZxlmVx7Lpdle5yZMGZB95KwX/ElNe5szvXcK4JJe4NhFO/nkNstRsAejd27xG7454+AZi79q7nMrC0m3ejCnmZ+A9NCS3znahGBbk/81cyUuxCFQrOn4rKlcX3oAo54eSFqfwL/A8AWPl86i/wEn1ZRU2iICojQIMsGo5toGLquu2IugXAnKl5ShLc+e80TDtET6jIi2X/Bd/HDFXNqwD11TTBW1RQtOqickwntEENTyqh9JPshLT8gb3YRX/OP8MZ1HBUX/hnAj7imMJJaXv6hPAOxVTu+NqmJcr0Q0Eeps+PVHESVei/n/BeE1copsMK0wFB1InqFaa1EyGonjS20qvpKcIw1dn0r6ZN+itMPS9uUGb/PzdNEUSdbsV3ipP3r4tSCKyI/IQOGJ8pjLqmvk9HBNEV+77vJz1OLsU1cynP83ByqVxomwqcQlqqVpL6avqBEk6dsNY0DZIa6RzVa5uiLCiZoP6aU7QTfmhWJhqswiRUD+927tKinKLOpEJj/gkXOCt/IdLZUAlFqqAsACvVCz5IZYmrak991Y2KIoWov4Snx/eQt8Qd7qU0MKEW/mOBOfdn1cJXCap+gH8fGRYPbvZvRnQXd7xQiOzZFQohQSb2ICqj6lzSx6pkueBHvzT9C4SjoA9NuRh31I8E8xC0WfAVKSn/zyYx7GVALwCwsZymxP+HG8fJNJUj22EfErC+bkpxERVCfNQ9g331IF0BgIjlRVFUVZHnLNpT+9FP4BNslYOD/4ToDLbFa2So2rvpEpgYY3N+hrHU2WOsArlDFf1QN40Q3rn+WDaneugLtidZ6ZLmQ+n7yULsSVOpyjlPljSgHIp0H7bgzNUpbuNHiX8ct6SuZnRXgKoWSeKdB/I+nkcoFUPlYCYrEPXiPMUvTAmRfyV6tPxPpqT+pF5D8Scx4jR3RH1DfV+nW47TBmFcIW/O864s6i75dcKbHN66JwAbqGZB+gLhdHjnJxAWzZNz81NTQqbmbYd/cEY3I+tMperybOI9XsukXfskKCkQTF36BlWYyyQwNPWTZvMiQAS5QsHsNfHmDf5QrFvzP9j62cTSGL1uJX2H8E0LlVBwG1N/dd1OFYpJtzvymSmhW32rMItM8UGEmirf6LofUo3LJzWSZpO4Cl1ic0QXfLpFO3I4Uosf6YXAzezfUELhW4pPtyS2AwCwkm9javs2NRweXjmYEbS11ZOg3EStf6RXWqvzn67o31Uls9npCb2dtOQuVjt+mLAd829MBbMlGg6qzwrXqVp7jAiOWYr/Cs8jls5VMG5taquXhpVbm8Z2wj9Umu17+gTcTs+X7oOtFfCTDVGm32KuT5ubi+o3bq8ytbCmwmCTHOo7xDikwqzXD7mWybioEo72zs3PSIz3qUjzne5qU9PVr/jjDOYYd1LK49NbRM0feGr3l682NX6JPLzLlBo+8EzfsJVeTHlndkhh4h1hfyE2zPxn7bfP61WNtn7IdX6fwYzWqPCn+7bAyNQoRw3HN5oa7afqP3ZjDjUzHbar8nwnHn6NqVEpLaz3Y/oH0JxL9ikte0gAAAAASUVORK5CYII=");
            $table->string("bio")->default("");
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
