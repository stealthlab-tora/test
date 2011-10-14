package com.template;

import com.template.R;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;
import android.text.SpannableStringBuilder;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.Toast;
import java.util.ArrayList;
import java.util.List;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.protocol.HTTP;

public class LoginActivity extends AbstractActivity {
	SharedPreferences sp = null;

	@Override
	protected void onCreate() {

		setContentView(R.layout.login);

		// Stored email and password by Remember function
		sp = PreferenceManager.getDefaultSharedPreferences(this);
		String defaultEmail = sp.getString("email", null);
		String defaultPassword = sp.getString("password", null);

		((EditText) findViewById(R.id.editTextUser)).setText(defaultEmail);
		((EditText) findViewById(R.id.editTextPassword)).setText(defaultPassword);

		Button btn = (Button) this.findViewById(R.id.buttonLogin);
		btn.setOnClickListener(new OnClickListener() {
			public void onClick(View arg0) {
				Button01_OnClick();
			}
		});
	}

	private void Button01_OnClick() {

		EditText email = (EditText) findViewById(R.id.editTextUser);
		String emailStr = ((SpannableStringBuilder) email.getText()).toString();
		
		EditText password = (EditText) findViewById(R.id.editTextPassword);
		String passwordStr = ((SpannableStringBuilder) password.getText()).toString();

		Log.d("Login", "email:" + emailStr + " ,password:" + passwordStr);

		HttpClient httpClient = new DefaultHttpClient();
		HttpPost httpPost = new HttpPost(
				"http://phpapi-az.dotcloud.com/api/LoginController.php");

		List<BasicNameValuePair> params = new ArrayList<BasicNameValuePair>();
		params.add(new BasicNameValuePair("email", emailStr));
		params.add(new BasicNameValuePair("password", passwordStr));

		UrlEncodedFormEntity entity = null;
		String response = null;
		try {
			entity = new UrlEncodedFormEntity(params, HTTP.UTF_8);
			httpPost.setEntity(entity);
			ResponseHandler<String> responseHandler = new BasicResponseHandler();
			response = httpClient.execute(httpPost, responseHandler);
			Log.d("Login Status", response);
			if (response.equals("false")) {
				Toast.makeText(getApplicationContext(), "Login failed", 10000).show();
				return;
			}
		} catch (Exception e) {
			e.printStackTrace();
			Toast.makeText(getApplicationContext(), "Login failed", 10000).show();
			return;
		}

		alertDialog();
		rememberEmailAndPassword(emailStr, passwordStr);

		Intent intent = new Intent(this, com.template.MainActivity.class);
		startActivityForResult(intent, 10); // 10 is Request Code
	}

	private void alertDialog() {
		AlertDialog.Builder AlertDlgBldr = new AlertDialog.Builder(this);
		AlertDlgBldr.setTitle("Welcome");
		AlertDlgBldr.setMessage("Login succeeded");
		AlertDlgBldr.setPositiveButton("ok", new DialogInterface.OnClickListener() {
			public void onClick(DialogInterface dialog, int which) {
			}
		});
		AlertDlgBldr.setNegativeButton("cancel", new DialogInterface.OnClickListener() {
			public void onClick(DialogInterface dialog, int which) {
				Toast.makeText(getApplicationContext(), "Cancelled", 10000).show();
				return;
			}
		});
		AlertDialog AlertDlg = AlertDlgBldr.create();
		AlertDlg.show();
	}

	private void rememberEmailAndPassword(String email, String password) {

		SharedPreferences.Editor sped = sp.edit();

		CheckBox rememberEmail = (CheckBox) findViewById(R.id.checkBoxRememberEmail);
		if (rememberEmail.isChecked()) {
			sped.putString("email", email);
			Log.d("check", "put email to SharedPreference");
		} else {
			sped.remove("email");
		}

		CheckBox rememberPassword = (CheckBox) findViewById(R.id.checkBoxRememberPassword);
		if (rememberPassword.isChecked()) {
			sped.putString("password", password);
			Log.d("check", "put password to SharedPreference");
		} else {
			sped.remove("password");
		}

		sped.commit();
	}

}