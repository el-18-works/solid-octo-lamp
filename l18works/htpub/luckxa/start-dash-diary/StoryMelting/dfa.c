int
main()
{
	int i,j;

	printf("(N, S)\t {N:Narrator, S:Section}\n");
	printf("======================================\n");

	for (i=j=2;i!=2 || j!=3;)
	{
		printf("(%d, %d)\n", i+1, j+1);

		if (i==2)
			j++;
		if (j==3)
			j=0;

		if (i>0)
			i--;
		else
			i=2;
	}

	return 0;
}
