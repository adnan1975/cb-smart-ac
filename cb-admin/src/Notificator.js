import React, {Component} from 'react';
import SimpleSnackbar from './SimpleSnackbar';


export default class Notificator extends Component {
    constructor(props) {
        super(props);

        this.state = {
            items: []
        };
    };



    componentWillMount() {
        this.setState({items: []});
    }
    componentDidMount() {
        this.timer = setInterval(()=> this.getItems(), 10000);

    }

    componentWillUnmount() {
        clearInterval(this.timer)
        this.timer = null; // here...
    }

    getItems() {
        fetch("http://104.248.180.30/api/v1/alerts")
            .then(result => result.json())
            .then(result => this.setState({ items: result}))

        ;

        console.log("getItems called");

    }

    render() {
        return <div><SimpleSnackbar queue={this.state.items} /> </div> ;

    }

}
