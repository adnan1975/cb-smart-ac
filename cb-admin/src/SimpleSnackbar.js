import React from 'react';
import Snackbar from '@material-ui/core/Snackbar';
import IconButton from '@material-ui/core/IconButton';
import CloseIcon from '@material-ui/icons/Close';
import PropTypes from 'prop-types';
import {withStyles} from '@material-ui/core/styles';

const styles = theme => ({
    close: {
        padding: theme.spacing.unit / 2,
    },
});

class SimpleSnackbar extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            open: false,
            messageInfo: {},
            queue: []
        };

    };


    componentDidUpdate(prevProps) {
        console.log("queue in  update " + this.props.queue);
        if (this.props.queue !== prevProps.queue) {

            this.processQueue();
        }
    }


    processPopQueue = () => {

        if (this.props.queue.length > 0) {
            this.setState({
                messageInfo: this.props.queue.pop(),
                open: true,
            });
        }
    };

    processQueue = () => {

        if (this.props.queue.length > 0) {
            this.setState({
                messageInfo: this.props.queue.shift(),
                open: true,
            });
        }
    };

    handleClose = (event, reason) => {
        if (reason === 'clickaway') {
            return;
        }
        this.setState({open: false});
    };

    handleExited = () => {
        this.processQueue();
    };

    render() {
        const {classes} = this.props;
        const {messageInfo} = this.state;

        return (
            <div>

                <Snackbar
                    key={new Date().getTime()}
                    anchorOrigin={{
                        vertical: 'bottom',
                        horizontal: 'left',
                    }}
                    open={this.state.open}
                    autoHideDuration={6000}
                    onClose={this.handleClose}
                    onExited={this.handleExited}
                    ContentProps={{
                        'aria-describedby': 'message-id',
                    }}
                    message={<span
                        id="message-id">This Device Has more than 9 ppm Carbon Mono Oxide {messageInfo}</span>}
                    action={[

                        <IconButton
                            key="close"
                            aria-label="Close"
                            color="inherit"
                            className={classes.close}
                            onClick={this.handleClose}
                        >
                            <CloseIcon/>
                        </IconButton>,
                    ]}
                />
            </div>
        );
    }
}

SimpleSnackbar.defaultProps = {processedDevices: []};

SimpleSnackbar.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(styles)(SimpleSnackbar);
