<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicDbStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('QuantityTypes', function (Blueprint $table) {
            $table->increments('QuantityTypeID');
            $table->string('QuantityName', 50)->nullable();
            $table->string('QuantityDescription')->nullable();
        });

        Schema::create('FoodTypes', function (Blueprint $table) {
            $table->increments('FoodTypeID');
            $table->string('FoodTypeName', 50)->nullable();
            $table->text('Comment')->nullable();
        });

        Schema::create('DonorTypes', function (Blueprint $table) {
            $table->increments('DonorTypeID');
            $table->string('Name', 50)->nullable();
            $table->string('Description', 50)->nullable();
        });

        Schema::create('CsoOrganizations', function (Blueprint $table) {
            $table->increments('CsoOrganizationID');
            $table->string('Name', 50)->nullable();
            $table->string('Description', 50)->nullable();
        });

        Schema::create('DonorOrganizations', function (Blueprint $table) {
            $table->increments('DonorOrganizationID');
            $table->string('Name', 50)->nullable();
            $table->string('Description', 50)->nullable();
        });

        Schema::create('Locations', function (Blueprint $table) {
            $table->increments('LocationID');
            $table->string('LocationName', 50)->nullable();
            $table->string('Description', 50)->nullable();
        });

        Schema::create('Csos', function (Blueprint $table) {
            $table->increments('CsoID');
            $table->string('FirstName', 50)->nullable();
            $table->string('LastName', 50)->nullable();
            $table->string('Password');
            $table->string('Phone', 50)->nullable();
            $table->string('Email', 50)->unique();
            $table->integer('CsoOrganizationID')->unsigned();
            $table->integer('LocationID')->unsigned();
            $table->string('ImageURL')->nullable();
            $table->boolean('Notifications')->default(0);
            $table->float('Location_x')->nullable();
            $table->float('Location_y')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('Csos', function (Blueprint $table) {
            $table->foreign('CsoOrganizationID')->references('CsoOrganizationID')->on('CsoOrganizations');
            $table->foreign('LocationID')->references('LocationID')->on('Locations');
        });

        Schema::create('Donors', function (Blueprint $table) {
            $table->increments('DonorID');
            $table->string('FirstName', 50)->nullable();
            $table->string('LastName', 50)->nullable();
            $table->string('Password');
            $table->string('Phone', 50)->nullable();
            $table->string('Email', 50)->unique();
            $table->integer('DonorOrganizationID')->unsigned();
            $table->integer('DonorTypeID')->unsigned();
            $table->integer('LocationID')->unsigned();
            $table->string('ImageURL')->nullable();
            $table->boolean('Notifications')->default(0);
            $table->float('Location_x')->nullable();
            $table->float('Location_y')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('Donors', function (Blueprint $table) {
            $table->foreign('DonorOrganizationID')->references('DonorOrganizationID')->on('DonorOrganizations');
            $table->foreign('LocationID')->references('LocationID')->on('Locations');
            $table->foreign('DonorTypeID')->references('DonorTypeID')->on('DonorTypes');
        });

        Schema::create('Listings', function (Blueprint $table) {
            $table->increments('ListingID');
            $table->integer('DonorID')->unsigned();
            $table->text('Title')->nullable();
            $table->text('Description')->nullable();
            $table->integer('FoodTypeID')->unsigned();
            $table->float('Quantity')->nullable();
            $table->integer('QuantityTypeID')->unsigned();
            $table->datetime('DateListed')->nullable();
            $table->datetime('DateExpires')->nullable();
            $table->string('ImageURL', 255)->nullable();
            $table->time('PickupTimeFrom')->nullable();
            $table->time('PickupTimeTo')->nullable();
            $table->string('ListingStatus', 50)->nullable();
            // $table->datetime('RecurringType');
            // $table->datetime('RecurringWeekly');
            // $table->datetime('RecurringMonthly');
            $table->timestamps();
        });

        Schema::table('Listings', function (Blueprint $table) {
            $table->foreign('DonorID')->references('DonorID')->on('Donors');
            $table->foreign('FoodTypeID')->references('FoodTypeID')->on('FoodTypes');
            $table->foreign('QuantityTypeID')->references('QuantityTypeID')->on('QuantityTypes');
        });

        Schema::create('ListingOffers', function (Blueprint $table) {
            $table->increments('ListingOfferID');
            $table->integer('CsoID')->unsigned();
            $table->integer('ListingID')->unsigned();
            $table->string('OfferStatus', 50)->nullable();
            $table->float('Quantity')->nullable();
            $table->integer('BeneficiariesNo')->nullable();
            $table->string('VolunteerPickupName', 50)->nullable();
            $table->string('VolunteerPickupPhone', 20)->nullable();
            $table->timestamps();
        });

        Schema::table('ListingOffers', function (Blueprint $table) {
            $table->foreign('CsoID')->references('CsoID')->on('Csos');
            $table->foreign('ListingID')->references('ListingID')->on('Listings');
        });

        Schema::create('ListingMsgs', function (Blueprint $table) {
            $table->increments('ListingMsgID');
            $table->integer('ListingOfferID')->unsigned();
            $table->text('MsgText');
            $table->string('MsgStatus', 50)->nullable();
            $table->string('SenderType', 20)->nullable();
            $table->integer('SenderID')->unsigned();
            $table->timestamps();
        });

        Schema::table('ListingMsgs', function (Blueprint $table) {
            $table->foreign('ListingOfferID')->references('ListingOfferID')->on('ListingOffers');
        });

        Schema::create('LoginLog', function (Blueprint $table) {
            $table->increments('LoginLogID');
            $table->integer('UserUD')->unsigned();
            $table->string('UserType', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop foreign keys
        Schema::table('Csos', function (Blueprint $table) {
            $table->dropForeign(['CsoOrganizationID']);
            $table->dropForeign(['LocationID']);
        });
        Schema::table('Donors', function (Blueprint $table) {
            $table->dropForeign(['DonorOrganizationID']);
            $table->dropForeign(['LocationID']);
            $table->dropForeign(['DonorTypeID']);
        });
        Schema::table('Listings', function (Blueprint $table) {
            $table->dropForeign(['DonorID']);
            $table->dropForeign(['FoodTypeID']);
            $table->dropForeign(['QuantityTypeID']);
        });
        Schema::table('ListingOffers', function (Blueprint $table) {
            $table->dropForeign(['CsoID']);
            $table->dropForeign(['ListingID']);
        });
        Schema::table('ListingMsgs', function (Blueprint $table) {
            $table->dropForeign(['ListingOfferID']);
        });

        //drop tables
        Schema::dropIfExists('QuantityTypes');
        Schema::dropIfExists('FoodTypes');
        Schema::dropIfExists('DonorTypes');
        Schema::dropIfExists('CsoOrganizations');
        Schema::dropIfExists('DonorOrganizations');
        Schema::dropIfExists('Locations');
        Schema::dropIfExists('Csos');
        Schema::dropIfExists('Donors');
        Schema::dropIfExists('Listings');
        Schema::dropIfExists('ListingOffers');
        Schema::dropIfExists('ListingMsgs');
        Schema::dropIfExists('LoginLog');
    }
}
