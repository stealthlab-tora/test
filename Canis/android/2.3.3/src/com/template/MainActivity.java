package com.template;

import com.template.R;

import android.content.Intent;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;

public class MainActivity extends AbstractActivity {

	@Override
	protected void onCreate() {
		setContentView(R.layout.main);

		Button sellBtn = (Button) this.findViewById(R.id.sellButton);
		sellBtn.setOnClickListener(new OnClickListener() {
			public void onClick(View arg0) {
				sellButtonOnClick();
			}
		});

		Button buyBtn = (Button) this.findViewById(R.id.buyButton);
		buyBtn.setOnClickListener(new OnClickListener() {
			public void onClick(View arg0) {
				buyButtonOnClick();
			}
		});
	}

	protected void sellButtonOnClick() {
		Intent intent = new Intent(this, com.template.SellMainActivity.class);
		startActivityForResult(intent, 10); // 10 is Request Code

	}

	protected void buyButtonOnClick() {
		Intent intent = new Intent(this, com.template.BuyMainActivity.class);
		startActivityForResult(intent, 10); // 10 is Request Code

	}

}