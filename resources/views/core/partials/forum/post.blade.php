<div class="panel panel-primary" id="post-{{{ $post->id }}}">
    <div class="panel-heading">
        <a href="{{{ $post->topic->Route }}}" class="white-text">@if ($post->getArrayIndex() > 0)RE: @endif{{{ $post->topic->title }}}</a>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <center>
                    <img class="img-rounded" src="{{{ $post->user->getAvatarURL(true) }}}" height="80" width="80" />
                    <br /><br />
                    <strong><a href="{{{ $post->user->profileURL }}}">{{{ $post->user->name }}}</a></strong>
                    <br />
                    @foreach($post->user->roles as $role)
                        <span class="label label-{{{ ($role->is_superuser == 1) ? 'danger' : ($role->id == 3 ? 'success' : ($role->id == 1 ? 'danger' : 'warning')) }}}">
							{{{ $role->name }}}
						</span>
                    @endforeach
                    @if ($post->user->is_online == 1)
                        <br />
                        <span class="label label-success">
							Online
						</span>
                    @endif
                    <hr>
                    {{{ $post->user->posts()->count() }}} {{{ Pluralizer::plural('post', $post->user->posts()->count()) }}}
                    <br /><br />
                </center>
            </div>
            <div class="col-md-9">
                By <a href="{{{ $post->user->profileURL }}}">{{{ $post->user->name }}}</a>
                &raquo;
                <span data-type="tooltip" data-trigger="hover" data-original-title="{{{ $post->created_at->format('l \a\t g:h A') }}}">{{{ $post->created_at->diffForHumans() }}}</span>
                @if ($post->updated_at > $post->created_at)
                <span class="text-muted">
					- Last edited {{{ $post->updated_at->diffForHumans() }}}
				</span>
                @endif
                <span class="pull-right">
					@if ($post->canEdit)
                    <a class="btn btn-primary btn-xs" href="{{{ route('forum.get.posts.edit', $post) }}}" data-type="tooltip" data-original-title="Edit post">
                        <span class="fa fa-pencil"></span>
                    </a>
                    @endif
                    @if ($post->canDelete)
                    {!! Form::open(['route' => array('forum.post.posts.delete', $post), 'style' => 'display: inline;']) !!}
                    {!! Form::button('<i class="fa fa-trash"></i>', ['class' => 'btn btn-danger btn-xs', 'data-type' => 'tooltip', 'data-original-title' => 'Delete post', 'type' => 'submit', 'onclick' => 'return confirm(\'Are you sure you want to delete this post?\')']) !!}
                    {!! Form::close() !!}
                    @endif
                    @unless(!Auth::check())
                    <a class="btn btn-warning btn-xs" href="{{{ route('forum.get.posts.report', $post) }}}" data-type="tooltip" data-original-title="Report post">
                        <span class="fa fa-exclamation-triangle"></span>
                    </a>
                    @endunless
                </span>
                <hr>
                {!! Smilies::parse(Mentions::parse(Purifier::clean($post->content))) !!}
                <br />
                @if (Auth::check())
                    <span class="pull-right">
					    @if (Auth::id() != $post->user->id)
                            @if (!$post->isLikedBy(Auth::user()))
                                {!! Form::open(['route' => array('forum.post.posts.like', $post), 'style' => 'display: inline;']) !!}
                                <button data-type="tooltip" data-original-title="Give reputation" type="submit" class="btn btn-success btn-sm give-rep">
                                    <span class="fa fa-thumbs-o-up"></span>
                                </button>
                                {!! Form::close() !!}
                            @endif
                            @if ($post->isLikedBy(Auth::user()))
                                {!! Form::open(['route' => array('forum.post.posts.dislike', $post), 'style' => 'display: inline;']) !!}
                                <button data-type="tooltip" data-original-title="Remove reputation" type="submit" class="btn btn-danger btn-sm give-rep">
                                    <span class="fa fa-thumbs-o-down"></span>
                                </button>
                                {!! Form::close() !!}
                            @endif
                        @endunless
                        <button class="btn btn-default btn-sm count-rep" data-toggle="modal" data-target="#likes-{{{ $post->id }}}"><strong>{{{ $post->likes()->count() }}}</strong></button>
					</span>
                    <br />
                @endif
                <hr>
                @if ($post->user->getSetting("show_signature", true) == true && $post->user->getSetting("post_signature") != null)
                    {!! Smilies::parse(Purifier::clean($post->user->getSetting("post_signature"))) !!}
                @endif
            </div>
        </div>
    </div>
    @unless($post->likes()->count() == 0)
        <div class="panel-footer">
            {!! $post->getLikeNames() !!}
        </div>
        @endif
</div>

<!-- Likes modal -->
<div class="modal fade" id="likes-{{{ $post->id }}}" tabindex="-1" role="dialog" aria-labelledby="likesLabel{{{ $post->id }}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="likesLabel{{{ $post->id }}}">Post Likes</h4>
            </div>
            <div class="modal-body">
                @unless($post->likes()->count() == 0)
                    <ul class="list-group">
                        {!! $post->genLikesModalHTML(false) !!}
                    </ul>
                @endunless

                @unless($post->likes()->count() > 0)
                    <p>This post has not received any likes.</p>
                @endunless
            </div>
        </div>
    </div>
</div>