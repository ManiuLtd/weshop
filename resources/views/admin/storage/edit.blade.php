@extends("layouts.admin")

@section("content")
  <form action="{{ route("admin.storage.update", $storage->id) }}" method="POST">
    @csrf
    name: <input name="name" type="text" value="{{ $storage->name }}" required/><br>
    brand_id: <input name="brand_id" type="number" value="{{ $storage->brand_id }}" required><br>
    description: <input name="description" type="text" value="{{ $storage->description }}"><br>
    contact_name: <input name="contact_name" type="text" value="{{ $storage->address->contact_name or "" }}"><br>
    contact_tel: <input name="contact_tel" type="text" value="{{ $storage->address->contact_tel or "" }}"><br>
    province: <input name="province" type="text" value="{{ $storage->address->province }}" required><br>
    city: <input name="city" type="text" value="{{ $storage->address->city }}" required><br>
    city_adcode: <input name="city_adcode" type="text" value="{{ $storage->address->city_code }}" required><br>
    address-detail: <input name="detail" type="text" value="{{ $storage->address->detail }}"><br>
    @submit
  </form>
@endsection