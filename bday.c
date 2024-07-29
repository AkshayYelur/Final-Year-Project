#include<stdio.h>
#include<conio.h>
#include<dos.h>
void main()
{
    clrscr();
    char i[20];
    textcolor(ORANGE);
    printf("Enter The Name in Capital Latter")
    scanf("%s",&i);
    for (int j=0;j<20;j++)
    {
        printf("HAPPY BIRTHDAY %S \2 \n",i);
        delay(200);
    }
    getch();
}