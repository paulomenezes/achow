'use strict';

var React = require('react-native');
var Icon = require('react-native-vector-icons/Ionicons');
var Communications = require('react-native-communications');

const {
	AppRegistry,
	StyleSheet,
	Text,
	View,
	TouchableOpacity,
	Modal,
	TouchableHighlight,
	ListView,
	Image,
	AlertIOS,
	ActivityIndicatorIOS,
	ScrollView,
	IntentAndroid
} = React;

class Contact extends React.Component {
	constructor(props) {
		super(props);
	}

	render() {
		return (
			<View style={{ flex: 1 }}>
				<View style={ styles.btn }>
					<Icon.Button name="ios-telephone" backgroundColor="#03a9f4" onPress={() => Communications.phonecall('1532615686', true) }>
						(15) 3261-5686
					</Icon.Button>
				</View>

				<View style={ styles.btn }>
					<Icon.Button name="social-facebook" backgroundColor="#03a9f4" onPress={() => IntentAndroid.openURL('https://www.facebook.com/Achowpag') }>
						Página do Facebook
					</Icon.Button>
				</View>

				<View style={ styles.btn }>
					<Icon.Button name="social-facebook-outline" backgroundColor="#03a9f4" onPress={() => IntentAndroid.openURL('https://www.facebook.com/groups/1655880931318136') }>
						Grupo do Facebook
					</Icon.Button>
				</View>

				<View style={ styles.btn }>
					<Icon.Button name="social-instagram-outline" backgroundColor="#03a9f4" onPress={() => IntentAndroid.openURL('https://instagram.com/achowapp/') }>
						Instagram
					</Icon.Button>
				</View>

				<View style={ styles.btn }>
					<Icon.Button name="social-twitter" backgroundColor="#03a9f4" onPress={() => IntentAndroid.openURL('https://twitter.com/achowapp/') }>
						Twitter
					</Icon.Button>
				</View>

				<View style={ styles.btn }>
					<Icon.Button name="ios-email" backgroundColor="#03a9f4" onPress={() => IntentAndroid.openURL('mailto:contato@achow.com.br') }>
						contato@achow.com.br
					</Icon.Button>
				</View>

				<View style={ styles.btn }>
					<Icon.Button name="social-whatsapp" backgroundColor="#03a9f4">
						(15) 99763-6965
					</Icon.Button>
				</View>
			</View>
		);
	}
}

var styles = StyleSheet.create({
	btn: {
		margin: 10,
		marginBottom: 0
	}
});

module.exports = Contact;