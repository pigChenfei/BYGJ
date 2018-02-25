<!-- Game Plat Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_plat_id', 'Game Plat Id:') !!}
    {!! Form::number('game_plat_id', null, ['class' => 'form-control']) !!}
</div>

<!-- English Game Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('english_game_name', 'English Game Name:') !!}
    {!! Form::text('english_game_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Game Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_name', 'Game Name:') !!}
    {!! Form::text('game_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Game Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_code', 'Game Code:') !!}
    {!! Form::text('game_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Game Lines Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_lines', 'Game Lines:') !!}
    {!! Form::number('game_lines', null, ['class' => 'form-control']) !!}
</div>

<!-- Game Icon Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_icon_path', 'Game Icon Path:') !!}
    {!! Form::text('game_icon_path', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('status', false) !!}
        {!! Form::checkbox('status', '1', null) !!} 1
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('games.index') !!}" class="btn btn-default">Cancel</a>
</div>
