
var Page = React.createClass({
	render: function() {
		return (
			<DocumentPage url="/document/latest" />
		);
	}
});

var DocumentPage = React.createClass({
	getInitialState: function() {
		return {data: []};
	},
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
	componentDidMount: function() {
		this.loadCommentsFromServer();
		//setInterval(this.loadCommentsFromServer, this.props.pollInterval);
	},

	render: function() {
		return (
			<div className="pure-u-1 pure-u-lg-3-5 pure-u-md-4-5">
				<div className="content box">
					<h1>Documents</h1>
					<DocumentList data={this.state.data} />
				</div>
			</div>
		);
	}
});

var DocumentList = React.createClass({

	handleDocumentClick: function(key) {
		console.log(key);
	},

	render: function() {
		var self = this;
		var documentNodes = this.props.data.map(function (doc) {
			return (
				<Document key={doc.id} doc={doc} onClickEvent={self.handleDocumentClick.bind(self, doc.id)} />
			);
		});

		return (
			<ul className="list lvl-0">
				{documentNodes}
			</ul>
		);
	}
});

var Document = React.createClass({
	onClickHandler: function(e) {
		e.preventDefault();
		this.props.onClickEvent();
	},
	render: function () {
		return (
			<li>
				<a href={ "/document/show/" + this.props.doc.id} onClick={this.onClickHandler}>
					<div className="meta">{this.props.doc.updated_at}</div>
                     {this.props.doc.title}
				</a>
			</li>
		);
	}
});


React.render(
	<Page />,
	document.getElementById('main')
);