<div class="well well-sm">
    <h4>Statistics</h4>
    <hr>
    <label>Users Registered:</label> {{{ $users->count() }}}
    <br />
    <label>Latest User:</label> {{{ $latestUser->name or 'Nobody' }}}
</div>