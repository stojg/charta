var data = [
	{author: "Pete Hunt", text: "This is one comment"},
	{author: "Jordan Walke", text: "This is *another* comment"}
];

var CommentBox = React.createClass({displayName: "CommentBox",
	loadCommentsFromServer: function() {
		$.ajax({
			url: this.props.url,
			dataType: 'json',
			cache: false,
			success: function(data) {
				this.setState({data: data});
			}.bind(this),
			error: function(xhr, status, err) {
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	},

	handleCommentSubmit: function(comment) {
		var comments = this.state.data;
		var newComments = comments.concat([comment]);
		this.setState({data: newComments});
		$.ajax({
			url: this.props.url,
			dataType: 'json',
			type: 'POST',
			data: comment,
			success: function(data) {
				this.setState({data: data});
			}.bind(this),
			error: function(xhr, status, err) {
				console.error(this.props.url, status, err.toString());
			}.bind(this)
		});
	},

	getInitialState: function() {
		return {data: []};
	},

	componentDidMount: function() {
		this.loadCommentsFromServer();
		setInterval(this.loadCommentsFromServer, this.props.pollInterval);
	},

	render: function() {
		return (
			React.createElement("div", {className: "commentBox"}, 
				React.createElement("h2", null, "Comments"), 
				React.createElement(CommentList, {data: this.state.data}), 
				React.createElement(CommentForm, {onCommentSubmit: this.handleCommentSubmit})
			)
		);
	}
});

var CommentList = React.createClass({displayName: "CommentList",
	render: function() {
		var commentNodes = this.props.data.map(function (comment) {
			return (
				React.createElement(Comment, {key: comment.id, author: comment.author}, 
                    comment.text
				)
			);
		});
		return (
			React.createElement("div", {className: "commentList"}, 
				 commentNodes
			)
		);
	}
});

var Comment = React.createClass({displayName: "Comment",
	render: function() {
		return (
			React.createElement("div", {className: "comment"}, 
				React.createElement("h3", {className: "commentAuthor"}, 
          this.props.author
				), 
        this.props.children
			)
		);
	}
});

var CommentForm = React.createClass({displayName: "CommentForm",
	handleSubmit: function(e) {
		e.preventDefault();
		var author = React.findDOMNode(this.refs.author).value.trim();
		var text = React.findDOMNode(this.refs.text).value.trim();
		if (!text || !author) {
			return;
		}
		this.props.onCommentSubmit({author: author, text: text});
		React.findDOMNode(this.refs.author).value = '';
		React.findDOMNode(this.refs.text).value = '';
		return;
	},
	render: function() {
		return (
			React.createElement("p", null, 
				React.createElement("form", {className: "commentForm", onSubmit: this.handleSubmit}, 
					React.createElement("input", {type: "text", placeholder: "Your name", ref: "author"}), 
					React.createElement("input", {type: "text", placeholder: "Say something...", ref: "text"}), 
					React.createElement("input", {type: "submit", value: "Post"})
				)
			)
		);
	}
});

React.render(
	React.createElement("div", {className: "comment box"}, 
		React.createElement(CommentBox, {url: "/data.json", pollInterval: 2000})
	),
	document.getElementById('comments')
);