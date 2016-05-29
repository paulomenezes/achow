var React = require('react-native');	

const {
	AppRegistry,
	StyleSheet,
	Text,
	View,
	TouchableOpacity,
	Modal,
	TouchableHighlight,
	ActivityIndicatorIOS,
	AlertIOS
} = React;

var Icon = require('react-native-vector-icons/Ionicons');
var Constants = require('../constants');
var Alert = require('../components/alert');

var styles = StyleSheet.create({
	backButton: {
		width: 21,
		height: 21,
		marginTop: 3,
		marginRight: 15
	}
});

class Like extends React.Component {
	constructor(props) {
		super(props);

		if (props.store) {
			this.state = {
				loading: false,
				user: props.user,
				store: props.store
			};
		} else {
			this.state = {
				loading: false,
				user: props.user,
				show: props.show
			};
		}
	}

	openSearch() {
		var state = this.state;

		var like = {};
		if (this.state.store) {
			like = {
    			idAccount: state.user.id,
    			idStore: state.store.id,
    			idVisitedType: 1
    		};
		} else {
			like = {
    			idAccount: state.user.id,
    			idShows: state.show.id,
    			idVisitedType: 1
    		};
		}

		fetch(Constants.URL + 'store_visited', {
				method: "POST",
	    		body: JSON.stringify(like),
	    		headers: Constants.HEADERS
			})
			.then((response) => response.json())
			.then((like) => {
				this.setState({
					loading: false
				});

				if (like.failed) {
					Alert('Gostei', 'Você já marcou como gostei esse evento');	
				} else {
					Alert('Gostei', 'Obrigado!');	
				}
			})
			.catch((error) => {
				Alert('Error', 'Houve um error ao se conectar ao servidor');
	    	});

			this.setState({
				loading: true
			})
	}

	openTimes() {
		fetch(Constants.URL + 'stores/' + this.state.store.id + '/schedule')
			.then((response) => response.json())
			.then((schedule) => {
				if (schedule.length > 0) {
					var text = '';
					for (var i = 0; i < schedule.length; i++) {
							 if (schedule[i].dayOfWeek == 0) text += 'Domingo: ';
						else if (schedule[i].dayOfWeek == 1) text += 'Segunda: ';
						else if (schedule[i].dayOfWeek == 2) text += 'Terça: ';
						else if (schedule[i].dayOfWeek == 3) text += 'Quarta: ';
						else if (schedule[i].dayOfWeek == 4) text += 'Quinta: ';
						else if (schedule[i].dayOfWeek == 5) text += 'Sexta: ';
						else if (schedule[i].dayOfWeek == 6) text += 'Sábado: ';

						if (schedule[i].closed == 1) 
							text += 'Fechado\n';
						else
							text += schedule[i].hourOpen + ' - ' + schedule[i].hourClose + '\n';
					};

					Alert('Horários de Funcionamento', text);
				} else 
					Alert('Horários de Funcionamento', 'Os horários de funcionamento não estão disponíveis.');
			}).catch((error) => {
				console.log(error);
				Alert('Horários de Funcionamento', 'Os horários de funcionamento não estão disponíveis.');
			});
	}

	render() {
		if (!this.state.loading) {
			return (
				<View style={{ flexDirection: 'row', marginTop: 4 }}>
					{ this.state.store ?
					<TouchableOpacity onPress={this.openTimes.bind(this)}>
						<Icon name="clock" color="#fff" size={25} style={styles.backButton} />
					</TouchableOpacity>
					: <View /> }

					<TouchableOpacity onPress={this.openSearch.bind(this)}>
						<Icon name="thumbsup" color="#fff" size={25} style={styles.backButton} />
					</TouchableOpacity>
				</View>
			)
		} else {
			return (
				<ActivityIndicatorIOS
					style={styles.backButton}
					color="white"
			        size="small" />
			)
		}
	}
}

module.exports = Like;