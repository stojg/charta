
var Page = React.createClass({displayName: "Page",
	render: function() {
		return (
			React.createElement(DocumentPage, {url: "/document/latest"})
		);
	}
});

var DocumentPage = React.createClass({displayName: "DocumentPage",
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
			React.createElement("div", {className: "pure-u-1 pure-u-lg-3-5 pure-u-md-4-5"}, 
				React.createElement("div", {className: "content box"}, 
					React.createElement("h1", null, "Documents"), 
					React.createElement(DocumentList, {data: this.state.data})
				)
			)
		);
	}
});

var DocumentList = React.createClass({displayName: "DocumentList",

	handleDocumentClick: function(key) {
		console.log(key);
	},

	render: function() {
		var self = this;
		var documentNodes = this.props.data.map(function (doc) {
			return (
				React.createElement(Document, {key: doc.id, doc: doc, onClickEvent: self.handleDocumentClick.bind(self, doc.id)})
			);
		});

		return (
			React.createElement("ul", {className: "list lvl-0"}, 
				documentNodes
			)
		);
	}
});

var Document = React.createClass({displayName: "Document",
	onClickHandler: function(e) {
		e.preventDefault();
		this.props.onClickEvent();
	},
	render: function () {
		return (
			React.createElement("li", null, 
				React.createElement("a", {href:  "/document/show/" + this.props.doc.id, onClick: this.onClickHandler}, 
					React.createElement("div", {className: "meta"}, this.props.doc.updated_at), 
                     this.props.doc.title
				)
			)
		);
	}
});


React.render(
	React.createElement(Page, null),
	document.getElementById('main')
);