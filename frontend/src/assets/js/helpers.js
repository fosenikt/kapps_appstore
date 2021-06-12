function helpers_current_date(days)
{
	var MyDate = new Date();
	var MyDateString;

	MyDate.setDate(MyDate.getDate() + days);

	MyDateString = MyDate.getFullYear() + '-' + ('0' + (MyDate.getMonth()+1)).slice(-2) + '-' + ('0' + MyDate.getDate()).slice(-2);

	return MyDateString;
}